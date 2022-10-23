<script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('js/jquery.dataTables.js') ?>"></script>
<script src="<?= base_url('js/dataTables.bootstrap5.js') ?>"></script>
<script>
    $(document).ready(function() {
        let table = $('#users').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: `<?= base_url('admin/search') ?>`,
            columns: [
                { title: 'Employee #', data: 'emp_no',     name: 'auth_users.emp_no' },
                { title: 'Name',       data: 'name',       name: 'auth_user_details.name' },
                { title: 'Username',   data: 'username',   name: 'auth_users.username' },
                { title: 'Email',      data: 'email',      name: 'auth_users.email' },
                { title: 'Date added', data: 'date_added', name: 'auth_users.date_added'},
                {
                    data: 'emp_no', name: 'auth_users.emp_no',
                    render: function(data, type, row, meta) {
                        return `<div class="d-flex justify-content-center gap-1">
                                <a href="<?= base_url('admin/update') ?>/${data}" class="btn btn-outline-primary rounded-0">Update</a>
                                </div>`
                    }
                }
            ]
        })
    })
</script>