<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-8">
                <h2 class="my-3">Form Ubah Data</h2>
                <form action="/buku/update/<?= $buku['id']; ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="slug" value="<?= $buku['slug']; ?>">
                <input type="hidden" name="sampulLama" value="<?= $buku['sampul']; ?>">
                    <div class="row mb-3">
                        <label for="judul" class="col-sm-2 col-form-label">Judul</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control <?= ($validation->hasError('judul')) ? 'is-invalid' : ''; ?>" id="judul" name="judul" value="<?= (old('judul')) ? old('judul') : $buku['judul'] ?>" autofocus>
                            <div id="validationServer03Feedback" class="invalid-feedback"><?= $validation->getError('judul'); ?></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="penulis" class="col-sm-2 col-form-label">Penulis</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="penulis" name="penulis" value="<?= (old('penulis')) ? old('penulis') : $buku['penulis'] ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="penerbit" class="col-sm-2 col-form-label">Penerbit</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= (old('penerbit')) ? old('penerbit') : $buku['penerbit'] ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sampul" class="col-sm-2 col-form-label">Sampul</label>
                        <div class="col-sm-2 mt-3">
                            <img src="/img/<?= $buku['sampul']; ?>" class="img-thumbnail img-preview">
                        </div>
                        <div class="col-sm-8">
                        <div class="mb-3">
                            <label for="sampul" class="form-label mt-2">Pilih gambar</label>
                            <input class="form-control <?= ($validation->hasError('sampul')) ? 'is-invalid' : ''; ?>" type="file" id="sampul" name="sampul" onchange="previewImg()">
                            <div id="validationServer03Feedback" class="invalid-feedback"><?= $validation->getError('sampul'); ?></div>
                        </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah Data</button>
                    </form>
            </div>
        </div>
    </div>
<?= $this->endSection(); ?>