<div class="p-3">
    <form id="statusUpdateForm" action="{{ route('single.status.update', $order->id) }}" method="POST">
        @csrf
        <label class="form-label fw-semibold">Order Status</label>
        <select name="status" class="form-select form-select-lg mb-3">
            <option value="pending">Pending</option>
            <option value="accepted">Accepted</option>
            <option value="on-the-way">On The Way</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <button type="submit" class="btn btn-dark w-100 rounded-3">
            Update Status
        </button>
    </form>

</div>