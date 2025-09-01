<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">My To-Do List ✅</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto bg-white p-4 shadow rounded">
            <!-- Add Task -->
            <form id="taskForm" class="mb-4">
                @csrf
                <textarea name="title" class="border rounded w-full px-2 py-1 mb-2" placeholder="New task..." required></textarea>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">
                    ➕ Add Task
                </button>
            </form>

            <!-- Task List -->
            <ul id="taskList">
                @foreach ($tasks as $task)
                    <li data-id="{{ $task->id }}" class="flex justify-between items-center mb-2 border-b py-1">
                        <div>
                            <span class="{{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                                {{ $task->title }}
                            </span>
                            <small class="block text-xs text-gray-500">
                                Start: {{ $task->start_time ?? '-' }}
                                | Done: {{ $task->done_time ?? '-' }}
                            </small>
                        </div>
                        <div class="flex space-x-2">
                            <button class="toggleBtn text-green-600 text-sm">
                                {{ $task->is_completed ? 'Undo' : 'Mark Done' }}
                            </button>
                            <button class="deleteBtn text-red-500 text-sm">Delete</button>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Add Task (AJAX without reload)
        $('#taskForm').submit(function(e) {
            e.preventDefault();

            $.post("{{ route('tasks.store') }}", $(this).serialize(), function(response) {
                if (response.success) {
                    let task = response.task;

                    // Append new task to list
                    $('#taskList').prepend(`
                    <li data-id="${task.id}" class="flex justify-between items-center mb-2 border-b py-1">
                        <div>
                            <span>${task.title}</span>
                            <small class="block text-xs text-gray-500">
                                Start: ${task.start_time} | Done: -
                            </small>
                        </div>
                        <div class="flex space-x-2">
                            <button class="toggleBtn text-green-600 text-sm">Mark Done</button>
                            <button class="deleteBtn text-red-500 text-sm">Delete</button>
                        </div>
                    </li>
                `);

                    // Clear textarea
                    $('#taskForm textarea').val('');
                }
            });
        });

        // Toggle Complete (AJAX without reload)
        $(document).on('click', '.toggleBtn', function(e) {
            e.preventDefault();
            let li = $(this).closest('li');
            let id = li.data('id');
            let btn = $(this);

            $.ajax({
                url: '/tasks/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        let task = response.task;

                        // Update text style
                        let span = li.find('span');
                        if (task.is_completed) {
                            span.addClass('line-through text-gray-500');
                            btn.text('Undo');
                        } else {
                            span.removeClass('line-through text-gray-500');
                            btn.text('Mark Done');
                        }

                        // Update times
                        li.find('small').text(
                            `Start: ${task.start_time ?? '-'} | Done: ${task.done_time ?? '-'}`);
                    }
                }
            });
        });

        // Delete Task (AJAX without reload)
        $(document).on('click', '.deleteBtn', function(e) {
            e.preventDefault();
            let li = $(this).closest('li');
            let id = li.data('id');

            $.ajax({
                url: '/tasks/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        li.remove(); // remove from DOM
                    }
                }
            });
        });
    </script>
</x-app-layout>
