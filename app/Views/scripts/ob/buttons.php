<script src="<?= base_url('js/jquery-3.6.0.min.js') ?>"></script>
<script>
    $('#cancel-request').click(function() {
        let id = $(this).val();

        $.ajax({
            url: `<?= base_url('ob/cancel') ?>/${id}`,
            method: 'put',
            success: function(data)
            {
                if(data.length !== 0)
                {
                    let response = JSON.parse(data)
                    $('.modal-title').text(response.type)
                    $('.modal-body').text(response.message)
                    const modal = bootstrap.Modal.getOrCreateInstance('#staticBackdrop', {})
                    modal.show()
                    $('#understood, #close').click(function() {
                        location.href = `<?= base_url('ob') ?>`
                    })
                }
            }
        })
    })

    $('.delete-item').click(function() {
        let id = $(this).val();

        $.ajax({
            url: `<?= base_url('ob/delete') ?>/${id}`,
            method: 'delete',
            success: function(data)
            {
                if(data.length !== 0)
                {
                    let response = JSON.parse(data)
                    $('.modal-title').text(response.type)
                    $('.modal-body').text(response.message)
                    const modal = bootstrap.Modal.getOrCreateInstance('#staticBackdrop', {})
                    modal.show()
                    $('#understood, #close').click(function() {
                        location.reload()
                    })
                }
            }
        })
    })

    $('.edit-modal').click(function() {
        let id = $(this).val()

        $.ajax({
            url: `<?= base_url('ob/retrieveItem') ?>/${id}`,
            method: 'get',
            dataType: 'json',
            success: function(data)
            {
                $('#modal-date').val(data[0]['sched_date'])
                $('#modal-destination').val(data[0]['destination'])
                $('#modal-departure').val(data[0]['departure'])
                $('#modal-arrival').val(data[0]['arrival'])
                $('#modal-purpose').val(data[0]['purpose'])
                $('#modal-save-item').val(id)
            }
        })
    })

    $('#modal-save-item').click(function() {
        let id   = $(this).val()
        let date = $('#modal-date').val()
        let dest = $('#modal-destination').val()
        let arvl = $('#modal-arrival').val()
        let dptr = $('#modal-departure').val()
        let prps = $('#modal-purpose').val()

        $.ajax({
            url: `<?= base_url('ob/update') ?>/${id}`,
            method: 'put',
            data: {
                'sched_date': date,
                'destination': dest,
                'departure': dptr,
                'arrival': arvl,
                'purpose': prps
            },
            header: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(data) {
                if(data.length !== 0)
                {
                    const updateModal = bootstrap.Modal.getOrCreateInstance('#update-modal', {})
                    updateModal.hide()

                    let response = JSON.parse(data)
                    $('.modal-title').text(response.type)
                    $('.modal-body').text(response.message)
                    const modal = bootstrap.Modal.getOrCreateInstance('#staticBackdrop', {})
                    modal.show()
                    $('#understood, #close').click(function() {
                        location.reload()
                    })
                }
            }
        })
    })

    $('#transit').change(function() {
        let id      = <?= $request['id'] ?? '' ?>;
        let transit = $(this).val()

        $.ajax({
            url: `<?= base_url('ob/update') ?>/${id}`,
            method: 'put',
            data: {
                'transit': transit
            },
            header: {'X-Requested-With': 'XMLHttpRequest'},
        })
    })


</script>