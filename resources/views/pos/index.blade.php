<x-app-layout>
    <div class="flex h-[calc(100vh-80px)] gap-6 p-6">
        
        <!-- Left: Product Grid -->
        <div class="w-2/3 overflow-y-auto pr-2">
            
            <div class="mb-6 flex gap-4">
                <input type="text" placeholder="Search products..." class="w-full border-gray-300 rounded-lg shadow-sm">
                <select class="border-gray-300 rounded-lg shadow-sm">
                    <option>All Categories</option>
                    @foreach($categories as $cat)
                        <option>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                @php
                    $isOutOfStock = $product->stock <= 0;
                @endphp
                <!-- 
                   LOGIC UPDATE: 
                   1. We added an ID to the card to manipulate it if needed.
                   2. The onclick only fires if stock is > 0 initially.
                   3. Styling changes if stock is 0 (grayed out).
                -->
                <div id="product-card-{{ $product->id }}"
                     onclick="{{ $isOutOfStock ? '' : 'addToCart('.$product->id.', \''.$product->name.'\', '.$product->price.', '.$product->stock.')' }}" 
                     class="bg-white p-4 rounded-xl shadow-sm transition group relative border border-transparent
                     {{ $isOutOfStock ? 'opacity-60 cursor-not-allowed bg-gray-50' : 'cursor-pointer hover:ring-2 hover:ring-amber-500' }}">
                    
                    <div class="h-24 bg-amber-50 rounded-lg mb-3 flex items-center justify-center text-amber-600 font-bold text-2xl group-hover:bg-amber-100 transition">
                        {{ substr($product->name, 0, 1) }}
                    </div>
                    
                    <h3 class="font-bold text-gray-800 leading-tight">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500 mb-2">{{ $product->category }}</p>
                    
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-amber-600 font-bold">₱{{ number_format($product->price, 2) }}</span>
                        
                        <!-- ADDED ID: stock-badge-ID so we can update the number with JS -->
                        <span id="stock-badge-{{ $product->id }}" 
                              class="text-xs px-2 py-1 rounded-full {{ $isOutOfStock ? 'bg-gray-200 text-gray-500' : ($product->stock < 5 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600') }}">
                            {{ $product->stock }} left
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right: Cart Section -->
        <div class="w-1/3 bg-white rounded-2xl shadow-lg flex flex-col border border-gray-100">
            <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
                <h2 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                    <span>🛒</span> Current Order
                </h2>
            </div>
            
            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50">
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between mb-2 text-gray-600">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">₱0.00</span>
                </div>
                <div class="flex justify-between mb-6 text-2xl font-bold text-gray-900">
                    <span>Total</span>
                    <span id="cart-total">₱0.00</span>
                </div>
                <button onclick="processPayment()" class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold shadow-lg hover:bg-gray-800 transition transform active:scale-95">
                    Process Payment 💳
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let cart = [];

        function addToCart(id, name, price, stock) {
            let item = cart.find(i => i.id === id);
            let currentQty = item ? item.quantity : 0;

            // 1. Check if we have enough stock BEFORE adding
            if (currentQty >= stock) {
                return alert('No more stock available!');
            }

            // 2. Add to cart
            if (item) {
                item.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
            }

            renderCart();
            // 3. Update the Grid UI immediately
            updateGridStock(id, stock);
        }

        // Helper to update the "X left" badge on the product card
        function updateGridStock(id, initialStock) {
            let item = cart.find(i => i.id === id);
            let qtyInCart = item ? item.quantity : 0;
            let remaining = initialStock - qtyInCart;

            let badge = document.getElementById(`stock-badge-${id}`);
            if (badge) {
                badge.innerText = remaining + " left";
                
                // Change color based on remaining stock
                if (remaining <= 0) {
                    badge.className = "text-xs px-2 py-1 rounded-full bg-gray-200 text-gray-500 font-bold";
                } else if (remaining < 5) {
                    badge.className = "text-xs px-2 py-1 rounded-full bg-red-100 text-red-600";
                } else {
                    badge.className = "text-xs px-2 py-1 rounded-full bg-green-100 text-green-600";
                }
            }
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            let html = '';
            let total = 0;

            if(cart.length === 0) {
                container.innerHTML = '<div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50"><p>Cart is empty</p></div>';
                document.getElementById('cart-total').innerText = '₱0.00';
                document.getElementById('cart-subtotal').innerText = '₱0.00';
                return;
            }

            cart.forEach((item, index) => {
                total += item.price * item.quantity;
                html += `
                    <div class="flex justify-between items-center bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                        <div>
                            <div class="font-bold text-gray-800">${item.name}</div>
                            <div class="text-xs text-gray-500">₱${item.price} x ${item.quantity}</div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-amber-600">₱${(item.price * item.quantity).toFixed(2)}</span>
                            <button onclick="removeFromCart(${index}, ${item.id}, ${item.stock})" class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('cart-total').innerText = '₱' + total.toFixed(2);
            document.getElementById('cart-subtotal').innerText = '₱' + total.toFixed(2);
        }

        function removeFromCart(index, id, stock) {
            cart.splice(index, 1);
            renderCart();
            // When removing from cart, put the stock back on the grid display
            updateGridStock(id, stock);
        }

        function processPayment() {
            if(cart.length === 0) return alert('Cart is empty');

            fetch("{{ route('pos.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ cart: cart })
            })
            .then(res => res.json())
            .then(data => {
                if(data.error) {
                    alert('Error: ' + data.error);
                } else {
                    alert('Transaction Successful! Receipt: ' + data.transaction.transaction_code);
                    cart = [];
                    renderCart();
                    window.location.reload(); 
                }
            })
            .catch(err => alert('Something went wrong.'));
        }
    </script>
</x-app-layout>