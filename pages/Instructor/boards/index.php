<?php
declare(strict_types=1);

// require __DIR__ . '/../../includes/helpers.php';
require __DIR__ . '/../../../config/app.php';
require __DIR__ . '/../../../includes/auth.php';

$user = $_SESSION['user'];
require __DIR__ . '/../../../includes/header.php';
?>
 <div class="p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Backend</h1>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                    <span>▼</span> Filter <span class="bg-gray-200 px-2 py-1 rounded text-sm">0</span>
                </button>
                <button class="p-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">⛶</button>
                <button class="p-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">⋮</button>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="flex gap-4 overflow-x-auto pb-4">
            <!-- Backlog Column -->
            <div class="flex-shrink-0 w-80">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Backlog</h2>
                        <button class="text-gray-500 hover:text-gray-700 text-xl">+</button>
                    </div>
                    <div class="p-3 min-h-[500px] space-y-3" data-column="backlog">
                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded">New</span>
                                <span class="text-gray-400 text-sm">#6266</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-blue-600 font-semibold text-sm">FEATURE</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Added Collect E-Sim Card</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(MOT)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planned Column -->
            <div class="flex-shrink-0 w-80">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Planned</h2>
                        <button class="text-gray-500 hover:text-gray-700 text-xl">+</button>
                    </div>
                    <div class="p-3 min-h-[500px] space-y-3" data-column="planned">
                        <!-- Empty -->
                    </div>
                </div>
            </div>

            <!-- In Progress Column -->
            <div class="flex-shrink-0 w-80">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">In progress</h2>
                        <button class="text-gray-500 hover:text-gray-700 text-xl">+</button>
                    </div>
                    <div class="p-3 min-h-[500px] space-y-3" data-column="inprogress">
                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">In progress</span>
                                <span class="text-gray-400 text-sm">#6439</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-red-600 font-semibold text-sm">BUG</span>
                                <span class="text-gray-700 text-sm ml-1">Backend – Error when editing an event:</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">In progress</span>
                                <span class="text-gray-400 text-sm">#6467</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Payment Method Management API</span>
                            </div>
                            <div class="text-gray-500 text-xs mb-2">my_payment_method</div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">NS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Column -->
            <div class="flex-shrink-0 w-80">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Testing</h2>
                        <button class="text-gray-500 hover:text-gray-700 text-xl">+</button>
                    </div>
                    <div class="p-3 min-h-[500px] space-y-3" data-column="testing">
                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-cyan-100 text-cyan-700 text-xs rounded">In testing</span>
                                <span class="text-gray-400 text-sm">#6262</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-red-600 font-semibold text-sm">BUG</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Push Notification:</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(M...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">In progress</span>
                                <span class="text-gray-400 text-sm">#6413</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Sim Management</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">In progress</span>
                                <span class="text-gray-400 text-sm">#6406</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Event, Activity and Province</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded">In progress</span>
                                <span class="text-gray-400 text-sm">#6418</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - API Get Distance</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">NS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ready Column -->
            <div class="flex-shrink-0 w-80">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-700">Ready for</h2>
                        <button class="text-gray-500 hover:text-gray-700 text-xl">+</button>
                    </div>
                    <div class="p-3 min-h-[500px] space-y-3" data-column="ready">
                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">Closed</span>
                                <span class="text-gray-400 text-sm">#6262</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - Number</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">Closed</span>
                                <span class="text-gray-400 text-sm">#6467</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-purple-600 font-semibold text-sm">TASK</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - screen:</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">BP</div>
                            </div>
                        </div>

                        <div class="card bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md transition" draggable="true">
                            <div class="flex items-start justify-between mb-2">
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">Closed</span>
                                <span class="text-gray-400 text-sm">#6439</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-red-600 font-semibold text-sm">BUG</span>
                                <span class="text-gray-700 text-sm ml-1">Backend - tags can't</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-xs">Visit Cambodia(...)</span>
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white text-xs font-bold">NS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let draggedElement = null;

        // Get all cards
        const cards = document.querySelectorAll('.card');
        const columns = document.querySelectorAll('[data-column]');

        // Add drag event listeners to cards
        cards.forEach(card => {
            card.addEventListener('dragstart', handleDragStart);
            card.addEventListener('dragend', handleDragEnd);
        });

        // Add drop event listeners to columns
        columns.forEach(column => {
            column.addEventListener('dragover', handleDragOver);
            column.addEventListener('drop', handleDrop);
            column.addEventListener('dragleave', handleDragLeave);
        });

        function handleDragStart(e) {
            draggedElement = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        }

        function handleDragEnd(e) {
            this.classList.remove('dragging');
            columns.forEach(column => column.classList.remove('drag-over'));
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            this.classList.add('drag-over');
            return false;
        }

        function handleDragLeave(e) {
            this.classList.remove('drag-over');
        }

        function handleDrop(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            if (draggedElement) {
                // Append the dragged card to the new column
                this.appendChild(draggedElement);
                
                // Optional: Add animation
                draggedElement.style.animation = 'none';
                setTimeout(() => {
                    draggedElement.style.animation = '';
                }, 10);
            }

            return false;
        }
    </script>