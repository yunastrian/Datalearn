@extends('layouts.app')

@section('content')
<div class="container">
@isset(request()->msg)
    @if( request()->get('msg') == 1 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Pembuatan Materi Berhasil
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 2 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Perubahan Materi Berhasil Disimpan
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 3 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Materi Berhasil Dihapus
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 4 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Deskripsi Materi Berhasil Diubah
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 5 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Nama Kelas Berhasil Diubah
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 6 )
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Akses Tidak Diperbolehkan
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Pembuatan Materi Gagal
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endisset
    <div class="row justify-content">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><b>Kelas {{ $course->name }}</b></div>
                @if( count($topics) == 0 )
                    <div class="card-body">Tidak ada Materi</div>
                @endif
                <div class="accordion-group" id="accordionTopics">
                    @foreach($topics as $index => $topic)
                        <div class="card">
                            <div class="card-header" id="heading<?php echo $topic->id; ?>">
                                <h6 class="mb-0">
                                    <a data-toggle="collapse" aria-expanded="false" aria-controls="collapse<?php echo $topic->id; ?>">
                                        <b>Materi {{ $index + 1 }}: {{ $topic->name }}</b>
                                    </a>
                                </h6>
                            </div>
                            <div id="collapse<?php echo $topic->id; ?>" class="collapse show" aria-labelledby="heading<?php echo $topic->id; ?>" data-parent="#accordionTopics">
                                <div class="card-body">
                                    {{ $topic->description }} <br/><br/>
                                    @if(Auth::user()->role == 1)
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $topic->id }}">Hapus</button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal{{ $topic->id }}">Edit Deskripsi</button>
                                        <a style="float: right;" href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>/edit" class="btn btn-primary" role="button">Isi Konten</a>
                                        <div class="modal fade" id="deleteModal{{ $topic->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ Request::url() }}/learn/{{ $topic->id }}/delete" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Hapus Materi</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">Apakah anda yakin menghapus materi <b>{{ $topic->name }}</b>?</div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger">Hapus Materi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="editModal{{ $topic->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="<?php echo Request::url(); ?>/edit_topic/{{ $topic->id }}" method="post">
                                                        {{ csrf_field() }}
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit Materi</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="topic-name" class="col-form-label">Edit Judul Materi</label>
                                                                <input type="text" class="form-control" name="topic_name" id="topic-name" required="required" value="{{ $topic->name }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="topic-description">Edit Deskripsi</label>
                                                                <textarea class="form-control" name="topic_description" id="topic_description" rows="2">{{ $topic->description }}</textarea>
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
                                    @else
                                        <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>" class="btn btn-primary" role="button">Buka Materi</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div> 
            </div>
            @if(Auth::user()->role == 1)
                <br/>
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteCourse">
                    Hapus Kelas
                </button>
                <div class="modal fade" id="deleteCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ $course->id }}/delete" method="post">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Hapus Kelas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">Apakah anda yakin menghapus kelas <b>{{ $course->name }}</b>?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">Hapus Kelas</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nameCourse">
                    Edit Nama Kelas
                </button>
                <div class="modal fade" id="nameCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="{{ $course->id }}/edit" method="post">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Edit Nama Kelas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="topic-name" class="col-form-label">Nama Kelas</label>
                                        <input type="text" class="form-control" name="course_name" id="course-name" required="required" value="{{ $course->name }}">
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
                <button style="float: right;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                    Tambah Materi
                </button>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <form action="<?php echo Request::url(); ?>/learn/new" method="post">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Materi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="topic-name" class="col-form-label">Masukkan Judul Materi</label>
                                        <input type="text" class="form-control" name="topic_name" id="topic-name" required="required" placeholder="Judul Materi">
                                    </div>
                                    <div class="form-group">
                                        <label for="topic-description">Deskripsi</label>
                                        <textarea class="form-control" name="topic_description" id="topic_description" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Tambah Materi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-4">
            @if(Auth::user()->role == 0)
                <div class="card">
                    <div class="card-header"><b>Progress</b></div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                <th scope="col">Materi</th>
                                <th scope="col">Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($scores as $index => $score)
                                <tr>
                                <td>Materi {{ $index + 1 }}</td>
                                <td>{{ $score }}</td>
                                </tr>    
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <br/>
            @endif
            <div class="card">
                <div class="card-header"><b>Peserta Kelas</b></div>
                <div class="card-body">
                    {{ $teacher }} (Pengajar) <br/>
                    @foreach($students as $student)
                        {{ $student }} <br/>
                    @endforeach
                    @if(Auth::user()->role == 1)
                        <br/>
                        <a href="<?php echo $topic->id_course; ?>/grade" class="btn btn-primary" role="button">Lihat Nilai</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection