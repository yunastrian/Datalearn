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
                <div class="card-header">Topik Materi</div>
                <div class="card-body">
                    <div class="accordion-group" id="accordionTopics">
                        @if( count($topics) == 0 )
                            Tidak ada Materi
                        @endif
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
                                        {{ $topic->content }} <br/><br/>
                                        @if(Auth::user()->role == 1)
                                            <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>/edit" class="btn btn-primary" role="button">Edit Materi</a>
                                        @else
                                            <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>" class="btn btn-primary" role="button">Buka Materi</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> 
                </div>
            </div>
            @if(Auth::user()->role == 1)
                <br/>
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
                                        <input type="text" class="form-control" name="topic_name" id="topic-name" required="required" placeholder="Judul Topik">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Buat Topik</button>
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
                    <div class="card-header">Progress</div>
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
                <div class="card-header">Peserta Kelas</div>
                <div class="card-body">
                    {{ $teacher }} (Pengajar) <br/>
                    @foreach($students as $student)
                        {{ $student }} <br/>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection