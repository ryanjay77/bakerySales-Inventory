<x-app-layout>
    <div class="flex h-[calc(100vh-80px)] gap-6 p-6 relative">
        
        <!-- Left: Product Grid -->
        <div class="w-2/3 overflow-y-auto pr-2">
            
            <div class="mb-6 flex gap-4">
                <!-- SEARCH INPUT (Added ID and onkeyup event) -->
                <input type="text" id="search-input" onkeyup="filterGrid()" placeholder="Search products..." 
                       class="w-full border-gray-300 rounded-lg shadow-sm text-lg p-3">
                
                <!-- CATEGORY SELECT (Added ID and onchange event) -->
                <select id="category-select" onchange="filterGrid()" class="border-gray-300 rounded-lg shadow-sm text-lg p-3">
                    <option value="all">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6"> 
                @foreach($products as $product)
                @php
                    $isOutOfStock = $product->stock <= 0;
                @endphp
                
                <!-- PRODUCT CARD (Added 'product-card' class and data attributes for filtering) -->
                <div id="product-card-{{ $product->id }}"
                     data-name="{{ strtolower($product->name) }}"
                     data-category="{{ $product->category }}"
                     onclick="{{ $isOutOfStock ? '' : 'addToCart('.$product->id.', \''.$product->name.'\', '.$product->price.', '.$product->stock.')' }}" 
                     class="product-card bg-white p-6 rounded-2xl shadow-sm transition group relative border border-transparent
                     {{ $isOutOfStock ? 'opacity-60 cursor-not-allowed bg-gray-50' : 'cursor-pointer hover:ring-2 hover:ring-amber-500 hover:shadow-md' }}">
                    
                    <div class="h-40 bg-amber-50 rounded-xl mb-4 flex items-center justify-center text-amber-600 font-bold text-5xl group-hover:bg-amber-100 transition">
                        {{ substr($product->name, 0, 1) }}
                    </div>
                    
                    <h3 class="font-bold text-gray-800 text-lg leading-tight mb-1">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $product->category }}</p>
                    
                    <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                        <span id="stock-badge-{{ $product->id }}" 
                              class="text-sm px-3 py-1 rounded-full {{ $isOutOfStock ? 'bg-gray-200 text-gray-500' : ($product->stock < 5 ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600') }}">
                            {{ $product->stock }} left
                        </span>
                        <span class="text-amber-600 font-bold text-lg">₱{{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- No Results Message (Hidden by default) -->
            <div id="no-results" class="hidden text-center py-10 text-gray-500 text-lg">
                No products found matching your filter.
            </div>
        </div>

        <!-- Right: Cart Section -->
        <div class="w-1/3 bg-white rounded-2xl shadow-lg flex flex-col border border-gray-100">
            <div class="p-6 border-b border-gray-100 bg-gray-50 rounded-t-2xl">
                <h2 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                    <span>🛒</span> Current Order
                </h2>
            </div>
            
            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
                <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50">
                    <p>Cart is empty</p>
                </div>
            </div>

            <div class="p-8 bg-gray-50 border-t border-gray-200 rounded-b-2xl">
                <div class="flex justify-between mb-2 text-gray-600 text-lg">
                    <span>Subtotal</span>
                    <span id="cart-subtotal">₱0.00</span>
                </div>
                <div class="flex justify-between mb-6 text-3xl font-bold text-gray-900">
                    <span>Total</span>
                    <span id="cart-total">₱0.00</span>
                </div>
                
                <button onclick="openPaymentModal()" class="w-full bg-gray-900 text-white py-5 rounded-xl font-bold text-xl shadow-lg hover:bg-gray-800 transition transform active:scale-95">
                    Process Payment 💳
                </button>
            </div>
        </div>
    </div>

    <!-- PAYMENT MODAL -->
    <div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 transform transition-all scale-100">
            <div class="text-center mb-6">
                <div class="bg-green-100 text-green-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">₱</div>
                <h2 class="text-3xl font-extrabold text-gray-800">Confirm Payment</h2>
                <p class="text-gray-500">Enter cash amount received</p>
            </div>
            
            <div class="space-y-6">
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <span class="text-lg font-medium text-gray-600">Total Amount</span>
                    <span class="font-bold text-amber-600 text-3xl" id="modal-total">₱0.00</span>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 uppercase mb-2 ml-1">Cash Received</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xl font-bold">₱</span>
                        <input type="number" id="cash-received" 
                               class="w-full pl-10 pr-4 py-4 text-2xl font-bold text-gray-800 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-500/20 outline-none transition-all" 
                               placeholder="0.00" oninput="calculateChange()" autofocus>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <span class="text-lg font-medium text-gray-600">Change Due</span>
                    <span class="font-bold text-green-600 text-3xl" id="modal-change">₱0.00</span>
                </div>
            </div>

            <div class="mt-8 flex gap-4">
                <button onclick="closeModal()" class="w-1/2 py-4 rounded-xl font-bold text-lg text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                    Cancel
                </button>
                <button onclick="submitTransaction()" id="confirm-btn" disabled 
                        class="w-1/2 py-4 rounded-xl font-bold text-lg text-white bg-gray-300 cursor-not-allowed transition shadow-md">
                    Pay & Print
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let cart = [];
        let currentTotal = 0;

        // --- FILTERING LOGIC ---
        function filterGrid() {
            const searchText = document.getElementById('search-input').value.toLowerCase();
            const selectedCategory = document.getElementById('category-select').value;
            const cards = document.querySelectorAll('.product-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const productName = card.getAttribute('data-name');
                const productCategory = card.getAttribute('data-category');

                const matchesSearch = productName.includes(searchText);
                const matchesCategory = selectedCategory === 'all' || productCategory === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    card.style.display = ''; // Show
                    visibleCount++;
                } else {
                    card.style.display = 'none'; // Hide
                }
            });

            // Toggle "No Results" message
            const noResultsMsg = document.getElementById('no-results');
            if (visibleCount === 0) {
                noResultsMsg.classList.remove('hidden');
            } else {
                noResultsMsg.classList.add('hidden');
            }
        }

        // --- CART LOGIC ---
        function addToCart(id, name, price, stock) {
            let item = cart.find(i => i.id === id);
            let currentQty = item ? item.quantity : 0;

            if (currentQty >= stock) {
                return alert('No more stock available!');
            }

            if (item) {
                item.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1, stock });
            }

            renderCart();
            updateGridStock(id, stock);
        }

        function updateGridStock(id, initialStock) {
            let item = cart.find(i => i.id === id);
            let qtyInCart = item ? item.quantity : 0;
            let remaining = initialStock - qtyInCart;

            let badge = document.getElementById(`stock-badge-${id}`);
            if (badge) {
                badge.innerText = remaining + " left";
                if (remaining <= 0) {
                    badge.className = "text-sm px-3 py-1 rounded-full bg-gray-200 text-gray-500 font-bold";
                } else if (remaining < 5) {
                    badge.className = "text-sm px-3 py-1 rounded-full bg-red-100 text-red-600";
                } else {
                    badge.className = "text-sm px-3 py-1 rounded-full bg-green-100 text-green-600";
                }
            }
        }

        function renderCart() {
            const container = document.getElementById('cart-items');
            let html = '';
            currentTotal = 0;

            if(cart.length === 0) {
                container.innerHTML = '<div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-50"><p>Cart is empty</p></div>';
                document.getElementById('cart-total').innerText = '₱0.00';
                document.getElementById('cart-subtotal').innerText = '₱0.00';
                return;
            }

            cart.forEach((item, index) => {
                currentTotal += item.price * item.quantity;
                html += `
                    <div class="flex justify-between items-center bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                        <div>
                            <div class="font-bold text-gray-800 text-lg">${item.name}</div>
                            <div class="text-sm text-gray-500">₱${item.price} x ${item.quantity}</div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-amber-600 text-lg">₱${(item.price * item.quantity).toFixed(2)}</span>
                            <button onclick="removeFromCart(${index}, ${item.id}, ${item.stock})" class="text-red-400 hover:text-red-600 p-1">✕</button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            document.getElementById('cart-total').innerText = '₱' + currentTotal.toFixed(2);
            document.getElementById('cart-subtotal').innerText = '₱' + currentTotal.toFixed(2);
        }

        function removeFromCart(index, id, stock) {
            cart.splice(index, 1);
            renderCart();
            updateGridStock(id, stock);
        }

        // --- PAYMENT LOGIC ---
        function openPaymentModal() {
            if(cart.length === 0) return alert('Cart is empty');
            document.getElementById('payment-modal').classList.remove('hidden');
            document.getElementById('payment-modal').classList.add('flex');
            document.getElementById('modal-total').innerText = '₱' + currentTotal.toFixed(2);
            document.getElementById('cash-received').value = '';
            document.getElementById('modal-change').innerText = '₱0.00';
            document.getElementById('confirm-btn').disabled = true;
            document.getElementById('confirm-btn').classList.add('bg-gray-300', 'cursor-not-allowed');
            document.getElementById('confirm-btn').classList.remove('bg-green-600', 'hover:bg-green-700');
            setTimeout(() => document.getElementById('cash-received').focus(), 100);
        }

        function closeModal() {
            document.getElementById('payment-modal').classList.add('hidden');
            document.getElementById('payment-modal').classList.remove('flex');
        }

        function calculateChange() {
            const cash = parseFloat(document.getElementById('cash-received').value) || 0;
            const change = cash - currentTotal;
            const confirmBtn = document.getElementById('confirm-btn');

            document.getElementById('modal-change').innerText = '₱' + (change > 0 ? change.toFixed(2) : '0.00');

            if (cash >= currentTotal) {
                confirmBtn.disabled = false;
                confirmBtn.classList.remove('bg-gray-300', 'cursor-not-allowed');
                confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
            } else {
                confirmBtn.disabled = true;
                confirmBtn.classList.add('bg-gray-300', 'cursor-not-allowed');
                confirmBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
            }
        }

        function submitTransaction() {
            const confirmBtn = document.getElementById('confirm-btn');
            confirmBtn.innerText = 'Processing...';
            confirmBtn.disabled = true;

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
                    confirmBtn.innerText = 'Pay & Print';
                    confirmBtn.disabled = false;
                } else {
                    const cash = parseFloat(document.getElementById('cash-received').value).toFixed(2);
                    const change = (cash - currentTotal).toFixed(2);
                    alert(`Transaction Successful!\nReceipt: ${data.transaction.transaction_code}\n\nCash: ₱${cash}\nChange: ₱${change}`);
                    cart = [];
                    renderCart();
                    closeModal();
                    window.location.reload(); 
                }
            })
            .catch(err => {
                alert('Something went wrong.');
                confirmBtn.innerText = 'Pay & Print';
                confirmBtn.disabled = false;
            });
        }
    </script>
</x-app-layout>