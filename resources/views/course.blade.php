@extends('layouts.app')

@section('content')
<div class="container">
@isset(request()->msg)
    @if( request()->get('msg') == 1 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Pembuatan Topik Berhasil
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif( request()->get('msg') == 2 )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Perubahan Topik Berhasil Disimpan
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Pembuatan Topik Gagal
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
                        @foreach($topics as $index => $topic)
                            <div class="card">
                                <div class="card-header" id="heading<?php echo $topic->id; ?>">
                                    <h6 class="mb-0">
                                        <a data-toggle="collapse" aria-expanded="false" aria-controls="collapse<?php echo $topic->id; ?>">
                                            <b>Topik {{ $index + 1 }}: {{ $topic->name }}</b>
                                        </a>
                                    </h6>
                                </div>
                                <div id="collapse<?php echo $topic->id; ?>" class="collapse show" aria-labelledby="heading<?php echo $topic->id; ?>" data-parent="#accordionTopics">
                                    <div class="card-body">
                                        {{ $topic->content }} <br/> <br/>
                                        @if(Auth::user()->role == 1)
                                            <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>/edit" class="btn btn-primary" role="button">Buka Topik</a>
                                        @else
                                            <a href="<?php echo $topic->id_course; ?>/learn/<?php echo $topic->id; ?>" class="btn btn-primary" role="button">Buka Topik</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> 
                    @if(Auth::user()->role == 1)
                        <br/>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                        Tambah Topik
                        </button>
                    @endif
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <form action="<?php echo Request::url(); ?>/learn/new" method="post">
                                    {{ csrf_field() }}
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Tambah Topik</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="topic-name" class="col-form-label">Masukkan Judul Topik</label>
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
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Progress</div>
                <div class="card-body">
                    @foreach($topics as $index => $topic)
                        Topik {{ $index + 1 }}: 100/100 <br/>
                    @endforeach
                </div>
            </div>
            <br/>
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