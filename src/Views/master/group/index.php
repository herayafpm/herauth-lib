<?php $this->extend("{$_main_path}templates/layout") ?>
<?php $this->section('css') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<?php $this->endSection('css') ?>
<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <a role="button" class="btn btn-sm btn-success" href="<?= $url_add ?>">Tambah Group</a>
            </div>
            <div class="card-body">
                <table id="tableMaster" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center" width="10">No.</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Updated At</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-center" width="10">No.</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Updated At</th>
                            <th width="100">Aksi</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection('content') ?>
<?php $this->section('modal') ?>
<?php $this->endSection('modal') ?>
<?php $this->section('js') ?>
<!-- DataTables  & Plugins -->
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/jszip/jszip.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= herauth_asset_url('vendor/adminlte') ?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    var tableMaster = null;

    dataVue = {
        list: [],
        params: {},
        loadingApi: false,
        messageApi: '',
        dataApi: {},
        errorsApi: {},
        alertType: '',
    }

    methodsVue = {
        reloadDatatable: function() {
            tableMaster.ajax.reload(function(json) {
                vue.list = json.data
            })
        },
    }


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
            vue.reloadDatatable()
        })
    }
    async function purgeData(id) {
        await axiosValid.post("<?= $url_delete ?>" + id + "?purge=1").then((res) => {
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
            vue.reloadDatatable()
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
            vue.reloadDatatable()
        })
    }

    $(document).ready(function() {
        tableMaster = $("#tableMaster").DataTable({
            "responsive": true,
            "language": {
                "buttons": {
                    "pageLength": {
                        "_": "Tampil %d baris <i class='fas fa-fw fa-caret-down'></i>",
                        "-1": "Tampil Semua <i class='fas fa-fw fa-caret-down'></i>"
                    }
                },
                "lengthMenu": "Tampil _MENU_ data per hal",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Tampil halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)"
            },
            "dom": 'Bfrtip',
            "buttons": [
                "copy", "csv", "excel", "pdf", "print", "colvis", {
                    extend: "pageLength",
                    attr: {
                        "class": "btn btn-primary"
                    },
                }
            ],
            "searching": false,
            "processing": true,
            "serverSide": true,
            "ordering": true, // Set true agar bisa di sorting
            "order": [
                [0, 'desc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                ['10 baris', '25 baris', '50 baris', 'Tampilkan Semua']
            ],
            "ajax": {
                "url": "<?= $url_datatable ?>", // URL file untuk proses select datanya
                "type": "POST",
                "data": function(d) {
                    return {
                        ...d,
                        ...vue.params
                    }
                }
            },
            "initComplete": function(settings, json) {
                vue.list = json.data;
            },
            'columnDefs': [{
                "targets": [4],
                "orderable": false
            }],
            "columns": [{
                    "data": "id",
                },
                {
                    "data": "nama",
                },
                {
                    "data": "deskripsi",
                },
                {
                    "data": "updated_at.date",
                    "render": function(dt, type, row, meta) {
                        return toLocaleDate(row.updated_at.date, 'LLL');
                    }
                },
                {
                    "data": "id",
                    "render": function(dt, type, row, meta) { // Tampilkan kolom aksi
                        var html = '';
                        if (row.nama !== 'superadmin') {
                            html += `
                            <a role="button" class="btn btn-sm btn-primary" href="<?= $url_edit ?>/${row.id}">
                                <i class="fas fa-fw fa-edit"></i>
                            </a>
                            `
                            if (row.deleted_at === null) {
                                html += `
                            <a role="button" class="btn btn-sm btn-danger hapusData" data-id="${row.id}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                            } else {
                                html += `
                            <a role="button" class="btn btn-sm btn-info restoreData" data-id="${row.id}">
                                <i class="fas fa-fw fa-recycle"></i>
                            </a>
                            <a role="button" class="btn btn-sm btn-danger purgeData" data-id="${row.id}">
                                <i class="fas fa-fw fa-trash"></i>
                            </a>
                            `
                            }
                        } else {
                            html += '-'
                        }
                        // html += '<button type="button" class="btn btn-link text-info" onClick="ubah(' + meta.row + ')"><i class="fa fa-fw fa-edit" aria-hidden="true" title="Edit ' +
                        //     row.kelas_nama + '"></i></button>'
                        // html += '<form method="POST" class="d-inline deleteData"><button type="button" class="btn btn-link text-danger" onClick="hapus(' + row.kelas_id + ')"><i class="fa fa-fw fa-trash" aria-hidden="true" title="Hapus ' + row.kelas_nama + '"></i></button></form>'
                        return html
                    }
                },
            ],
        });
        tableMaster.on('order.dt page.dt', function() {
            tableMaster.column(0, {
                order: 'applied',
                page: 'applied',
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
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
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
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
<?php $this->endSection('js') ?>