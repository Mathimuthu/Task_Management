<x-adminlte-modal id="taskTimelineModal" title="Task Status Timeline" theme="purple" icon="fas fa-history" size='lg'>
    <div id="taskTimelineContainer">
        <ul id="taskTimeline">
            <!-- Timeline will be inserted here dynamically -->
        </ul>
    </div>
    <x-slot name="footerSlot" :null="true"></x-slot>
</x-adminlte-modal>
<style>
    .timeline-list {
        padding: 0;
        list-style: none;
    }

    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 15px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 5px;
        width: 12px;
        height: 12px;
        background: #6a0dad;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(106, 13, 173, 0.3);
    }

    .status-date {
        font-size: 12px;
        color: gray;
    }

    .status-desc {
        font-size: 14px;
        font-weight: bold;
    }
</style>
