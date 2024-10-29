@extends('master.dashboard.master')

@section('content')
  <!-- Begin Page Content -->
  <div class="container-fluid">

      <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">User Control Panel</h1>
      <p class="mb-4"> List of your ucp </p>

      @if (isset($ucp))
      <!-- DataTales Example -->
      <div class="card shadow mb-4">
          <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                          <tr>
                              <th>UCP Name</th>
                              <th>PIN</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{{ $ucp->ucp }}</td>
                              <td>{{ $ucp->verifycode }}</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
      @else
      <div class="card shadow mb-4">
        <div class="card-body">
          <h1>Opps like that you don't have any ucp</h1>
          <button class="btn btn-primary center" onclick="showModalUcp()"> Create One </button>
        </div>
      
      </div>
      @endif

  </div>
  <!-- /.container-fluid -->
@endsection

@push('js')
  <script>
    $(document).ready(function () {
      // csrf token
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });




      
    });

    function createUcp() {
      let pin = $("#pin").val();

      $.ajax({
        type: "POST",
        url: "{{ route('ucp.create') }}",
        data: {
          pin: pin
        },
        success: function (response) {
          if(response.code == 200) {
            Swal.fire({
              title: "Success",
              text: response.message,
              icon: "success"
            });

            // reload page
            location.reload();
          }
        }
      });

    }

    function showModalUcp(){
      console.log("asd");
      $("#create_ucp").modal('show');
    }
  </script>
@endpush