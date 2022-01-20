<table id="tableMaster" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-center" width="10">No.</th>
            <th>Nama</th>
            <th>Desc</th>
            <th width="100">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        foreach ($datas as $data) :
        ?>
            <tr>
                <td class="text-center"><?= $no ?></td>
                <td><?= $data['nama'] ?></td>
                <td><?= empty($data['desc']) ? '-' : $data['desc'] ?></td>
                <td>
                    <?php if ($data['nama'] !== 'superadmin') : ?>
                        <a role="button" class="btn btn-sm btn-primary" href="<?= base_url($url_edit . "/" . $data['id']) ?>">
                            <i class="fas fa-fw fa-edit"></i>
                        </a>
                        <?php if ($data['deleted_at'] === null) : ?>
                            <a role="button" class="btn btn-sm btn-danger hapusData" data-id="<?= $data['id'] ?>">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                        <?php else : ?>
                            <a role="button" class="btn btn-sm btn-info restoreData" data-id="<?= $data['id'] ?>">
                                <i class="fas fa-fw fa-recycle"></i>
                            </a>
                            <a role="button" class="btn btn-sm btn-danger purgeData" data-id="<?= $data['id'] ?>">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                        <?php endif ?>
                    <?php else : ?>
                        -
                    <?php endif ?>
                </td>
            </tr>
        <?php
            $no++;
        endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-center">No.</th>
            <th>Nama</th>
            <th>Desc</th>
            <th>Aksi</th>
        </tr>
    </tfoot>
</table>
<script>
    async function hapusData(id) {
        await axiosValid.post("<?= $url_delete ?>" + id).then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            getTable()
        })
    }
    async function purgeData(id) {
        await axiosValid.post("<?= $url_delete ?>" + id+"?purge=1").then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            getTable()
        })
    }
    async function restoreData(id) {
        await axiosValid.post("<?= $url_restore ?>" + id).then((res) => {
            if (res.status !== 200) {
                Swal.fire({
                    title: res.data.message,
                    icon: 'error',
                })
            } else {
                Swal.fire({
                    title: res.data.message,
                    icon: 'success',
                })
            }
            getTable()
        })
    }
    $(document).ready(function() {
        $("#tableMaster").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tableMaster_wrapper .col-md-6:eq(0)');
        $("#tableMaster").on('click', '.hapusData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: 'Anda Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya, hapus!',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    hapusData(id)
                }
            })
        })
        $("#tableMaster").on('click', '.restoreData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: 'Anda Yakin ingin mengembalikan data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya, kembalikan!',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    restoreData(id)
                }
            })
        })
        $("#tableMaster").on('click', '.purgeData', function() {
            var id = $(this).data('id')
            Swal.fire({
                title: 'Anda Yakin ingin menghapus data ini selamanya?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                cancelButtonText: 'Tidak',
                confirmButtonText: 'Ya, hapus selamanya!',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    purgeData(id)
                }
            })
        })
    })
</script>