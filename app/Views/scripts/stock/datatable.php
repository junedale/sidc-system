<script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('js/jquery.dataTables.js') ?>"></script>
<script src="<?= base_url('js/dataTables.bootstrap5.js') ?>"></script>
<script>
    $(document).ready(function() {
        let table = $('#stock').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: `<?= base_url('stock/search') ?>`,
            columns: [
                { title: 'Control #', data: 'id' },
                { title: 'Requester', data: 'name' },
                { title: 'Status', data: 'status'},
                { title: 'Request Date', data: 'date_added'},
                {
                    data: 'id',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center gap-1">
                                <a href="<?= base_url('stock/view') ?>/${data}" class="btn btn-outline-secondary rounded-0">View</a>
                                <a href="<?= base_url('stock/update') ?>/${data}" class="btn btn-primary rounded-0">Update</a>
                                </div>`
                    }
                }
            ]
        })
    })
</script>