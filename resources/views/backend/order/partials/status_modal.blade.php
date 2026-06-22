<div class="p-3">
    <form id="statusUpdateForm" action="{{ route('single.status.update', $order->id) }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-semibold">Order Status</label>
            <select name="status" id="orderStatusSelect" class="form-select form-select-lg">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="on-the-way" {{ $order->status == 'on-the-way' ? 'selected' : '' }}>On The Way</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="return" {{ $order->status == 'return' ? 'selected' : '' }}>Return</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="mb-4 p-3 bg-danger bg-opacity-10 rounded-3 border border-danger border-opacity-25" id="returnChargeBox" style="display: {{ $order->status == 'return' ? 'block' : 'none' }};">
            <label class="form-label fw-semibold text-danger">Courier Return Penalty (৳)</label>
            <input type="number" name="return_charge" class="form-control form-control-lg border-danger shadow-sm" placeholder="e.g. 120" value="{{ $order->return_charge ?? 0 }}">
            <small class="text-danger mt-1 d-block opacity-75"><i class="bi bi-info-circle"></i> This amount will be recorded as a loss.</small>
        </div>

        <button type="submit" class="btn btn-dark w-100 rounded-3">
            Update Status
        </button>
    </form>
</div>

<script>
     $(document).ready(function() {
        $('#orderStatusSelect').on('change', function() {
            if ($(this).val() === 'return') {
                $('#returnChargeBox').slideDown();
            } else {
                $('#returnChargeBox').slideUp();
            }
        });
    });
</script>

