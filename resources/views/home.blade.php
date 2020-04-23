@extends('layouts.app')

@section('content')
<div class="container">
@isset(request()->msg)
    @if( request()->get('msg') == 1 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Profil Berhasil Diubah
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 2 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Pembuatan Kelas Berhasil
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 3 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Daftar Kelas Berhasil
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 4 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Kelas Berhasil Dihapus
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endisset
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Kelasku</b></div>
                <div class="card-columns card-body">
                    @if( count($enrolled) == 0 )
                        Tidak ada kelas yang diikuti
                    @endif
                    @foreach($enrolled as $enroll)
                        <div class="card course" style="width: 14rem">
                            <img src="img/course1.png" class="card-img-top" alt="No Picture">
                            <div class="card-header">{{ $enroll->name }}</div>
                            <a href="/course/<?php echo $enroll->id; ?>" class="stretched-link"></a>
                        </div>
                    @endforeach
                </div>
            </div>
            @if(Auth::user()->role == 1)
                <br/>
                <button style="float: right;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#newCourseModal">
                    Tambah Kelas
                </button>
                <div class="modal fade" id="newCourseModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="/course/new" method="post">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Kelas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="course-name" class="col-form-label">Masukkan Nama Kelas</label>
                                        <input type="text" class="form-control" name="course_name" id="course-name" required="required" placeholder="Nama Kelas">
                                    </div>
                                    <div class="form-group">
                                        <label for="course-description">Deskripsi</label>
                                        <textarea class="form-control" name="course_description" id="course_description" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Buat Kelas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><b>Profil</b></div>
                <div class="card-body profile">
                    <img src="img/profile.jpg" style="width:120px;height:120px;" alt="No Picture"> <br/> <br/>
                    <a id="profile-name">{{ $profile->name }}</a> <br/>
                    <a id="email">{{ $profile->email }}</a> <br/>
                    <a id="role">{{ $role }}</a> <br/><br/>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        Edit Profile
                    </button>
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="/profile" method="post">
                                    {{ csrf_field() }}
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Profil</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="topic-name" class="col-form-label">Nama</label>
                                            <input type="text" class="form-control" name="new_name" id="new_name" required="required" value="<?php echo $profile->name ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->role == 0)
        <div class="py-4 row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b>Kelas Tersedia</b></div>
                    <div class="card-body">
                        <div class="accordion" id="accordionCourses">
                            @foreach($courses as $index => $course)
                                <div class="card">
                                    <div class="card-header" id="heading<?php echo $course->id; ?>" style="transform: rotate(0);">
                                        <a class="stretched-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $course->id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $course->id; ?>">{{ $course->name }}</a> 
                                    </div>

                                    <div id="collapse<?php echo $course->id; ?>" class="collapse" aria-labelledby="heading<?php echo $course->id; ?>" data-parent="#accordionCourses">
                                        <div class="card-body">
                                            {{ $course->description }} <br/><br/>
                                            <form action="course/enroll" method="post">
                                                {{ csrf_field() }}
                                                <input style="display: none;" type="number" class="form-control" name="enroll_id" id="enroll_id" required="required" value="<?php echo $course->id ?>">
                                                <button type="submit" class="btn btn-primary">Daftar Kelas</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection