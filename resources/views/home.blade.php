@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kelasku</div>
                <div class="card-columns card-body">
                    <div class="card course" style="width: 14rem">
                        <img src="img/course1.png" class="card-img-top" alt="No Picture">
                        <div class="card-header">Pengenalan Spreadsheet</div>
                        <a href="/1" class="stretched-link"></a>
                    </div>
                    <div class="card course" style="width: 14rem">
                        <img src="img/course1.png" class="card-img-top" alt="No Picture">
                        <div class="card-header">Pengenalan Spreadsheet 2</div>
                        <a href="/2" class="stretched-link"></a>
                    </div>
                    <div class="card course" style="width: 14rem">
                        <img src="img/course1.png" class="card-img-top" alt="No Picture">
                        <div class="card-header">Pengenalan Spreadsheet</div>
                        <a href="/3" class="stretched-link"></a>
                    </div>
                    <div class="card course" style="width: 14rem">
                        <img src="img/course1.png" class="card-img-top" alt="No Picture">
                        <div class="card-header">Pengenalan Spreadsheet 4</div>
                        <a href="/4" class="stretched-link"></a>
                    </div>
                    <div class="card course" style="width: 14rem">
                        <img src="img/course1.png" class="card-img-top" alt="No Picture">
                        <div class="card-header">Pengenalan Spreadsheet 4</div>
                        <a href="/4" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Profil</div>
                <div class="card-body profile">
                    <img src="img/profile.jpg" style="width:120px;height:120px;" alt="No Picture"> <br/> <br/>
                    <a id="name">Kurniandha Sukma Yunastrian</a> <br/>
                    <a id="email">kurnia@gmail.com</a> <br/>
                    <a id="role">Pengajar</a> <br/>
                    <a id="edit" href="/edit_profile" class="btn btn-primary" role="button">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>
    <div class="py-4 row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Kelas Tersedia</div>
                <div class="card-body">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link stretched-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Spreadsheet Expert 1
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    Belajar tentang spreadsheet dengan level expert <br/>
                                    Pengajar : <br/> <br/>
                                    <a href="/enroll" class="btn btn-primary" role="button">Daftar Kelas</a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed stretched-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Expert 2
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    Belajar tentang spreadsheet dengan level expert part 2 <br/>
                                    Pengajar : <br/> <br/>
                                    <a href="/enroll" class="btn btn-primary" role="button">Daftar Kelas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    courses {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>