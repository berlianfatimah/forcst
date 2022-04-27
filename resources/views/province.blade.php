@extends('layouts/master')
@section('content')

<div class="col-md-12 grid-margin">
    <div class="row">
        <div class="col-md-5 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Tambah Data Provinsi</h4>
                {{-- <p class="card-description">
                  Tambah Data Aktual
                </p> --}}
                <form class="forms-sample" method="POST" action="/province">
                  @csrf
                  <div class="form-group row">
                    <label for="tahun" class="col-sm-3 col-form-label">Nama Povinsi</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="" name="provinsi" placeholder="Provinsi" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success mr-2">Tambah Data</button>
                  <button class="btn btn-light">Cancel</button>
                </form>
              </div>
            </div>
        </div>
      <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tabel Provinsi</h4>
                    @if(session('success'))
                      <div class="alert alert-success">
                        {{ session('success') }}
                      </div>   
                    @endif
                <div class="table-responsive">
                  <table id="" class="display expandable-table" style="width:100%">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Provinsi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($provinces as $province)
                        <tr>
                            <td>1</td>
                            <td>{{ $province->province }}</td>
                            <td>
                                <a href="/province/{{ $province->id }}/edit" class="btn btn-primary btn-sm">edit</a>
                                <form action="/province/{{ $province->id }}" method="POST" class="d-inline">
                                  <input type="submit" name="" id="" value="delete" class="btn btn-danger btn-sm">
                                  @csrf
                                  @method('DELETE')
                                </form>
                                {{-- <a href="" class="btn btn-danger btn-sm">delete</a> --}}
                              </td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
    
@endsection