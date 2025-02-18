<div class="flex space-x-4 p-4">
    @foreach (['To Do', 'In Progress', 'Done'] as $status)
        <div class="w-1/3 bg-gray-100 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold text-center mb-4">{{ $status }}</h3>

            <div id="{{ $status }}" class="min-h-[200px] p-2 bg-gray-200 rounded-md">
                @foreach ($tasks->where('status', $status) as $task)
                    <div class="p-3 bg-white shadow-md rounded-md cursor-pointer my-2" data-id="{{ $task->id }}"
                        data-status="{{ $status }}">
                        {{ $task->title }}
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['To Do', 'In Progress', 'Done'].forEach(status => {
            new Sortable(document.getElementById(status), {
                group: 'kanban',
                animation: 150,
                onEnd: function(evt) {
                    let tasks = [];
                    document.querySelectorAll(`#${status} div`).forEach(el => {
                        tasks.push({
                            id: el.getAttribute('data-id'),
                            status: status
                        });
                    });

                    Livewire.emit('updateTaskOrder', tasks);
                }
            });
        });
    });
</script>
