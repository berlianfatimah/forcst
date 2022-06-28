@extends('layouts/master')
@section('content')

<div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-normal">Single Exponential Smoothing</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Tabel Prediksi</h4>
            <div class="table-responsive">
              <table id="" class="display expandable-table" style="width:100%">
                <thead>
                  <tr>
                    <th>Provinsi</th>
                    <th>Tahun</th>
                    <th>Produksi Padi</th>
                    <th>Prediksi Produksi Padi</th>
                    <th>Absloute Error</th>
                    <th>Presentase Error</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($results as $result)
                  <tr>
                    <td>{{$provinsi->province}}</td>
                    <td>{{ $result['periode'] }}</td>
                    <td>{{ $result['aktual'] }}</td>
                    <td>{{ round($result['f'], 2) }}</td>
                    <td>{{ round($result['abs_e'], 2) }}</td>
                    <td>{{ round($result['percent_e'], 3) }}</td>
                  </tr>
                  @empty
                  @endforelse
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-7 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title">Perhitungan setiap nilai Alpha</p>
            <div class="table-responsive">
              <table class="table table-borderless expandable-table">
                <thead>
                  <tr>
                    <th>Nilai Alpha</th>
                    <th>Prediksi</th>
                    <th>MAPE</th>
                  </tr>  
                </thead>
                <tbody>
                  @forelse ($alpha as $item)
                  <tr>
                    <td>{{ $item['alpha'] }}</td>
                    <td>{{ $item['forecasting'] }}</td>
                    <td>{{ $item['mape'] }}</td>
                  </tr>
                  @empty
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-5 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Kesimpulan</h4>
            <hr>
            <div>
              <p>Peramalan hasil produksi padi periode selanjutnya sebesar {{ $optimal['forecasting'] }} Ton, dengan Mean Absolute Presentase Error (MAPE) sebesar {{ $optimal['mape'] }}% dan Alpha optimal sebesar {{ $optimal['alpha'] }} </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <canvas id="bar"></canvas>
          </div>
        </div>
        <div class="card mt-4">
          <div class="card-body">
            <canvas id="bar2"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
    
@endsection
@section('script')
	<script>
        var data = JSON.parse(`<?php echo $chartData; ?>`);

        var ctxBar = document.getElementById("bar").getContext("2d");
        var ctxBar2 = document.getElementById("bar2").getContext("2d");
        var myBar = new Chart(ctxBar, {
            type: 'line',
            data: {
                labels: data.alpha,
                datasets: [{
                    label: 'MAPE',
                    // backgroundColor: chartColors.color1,
                    data: data.mape,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                barRoundness: 1,
                title: {
                    display: true,
                    text: "Grafik Mean Absolute Percentage Error"
                },
                legend: {
                    display: false
                },
                
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10,
                        },
                        gridLines: {
                            drawBorder: false,
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        }
                    }]
                },

            }
        });
        var myBar2 = new Chart(ctxBar2, {
            type: 'line',
            data: {
                labels: data.periode,
                datasets: [{
                    label: 'Data Aktual',
                    // backgroundColor: chartColors.color1,
                    data: data.aktual,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'Prediksi',
                    fill: false,
                    borderColor: 'rgb(75, 100, 192)',
                    tension: 0.1,
                    data: data.forcasting,
                }]
            },
            options: {
                responsive: true,
                barRoundness: 1,
                title: {
                    display: true,
                    text: "Grafik Data Aktual dan Peramalan"
                },
                legend: {
                    display: false
                },
                
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10,
                        },
                        gridLines: {
                            drawBorder: false,
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        }
                    }]
                },

            }
        });
    </script>
@endsection