<!DOCTYPE html>
<head  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Jean Yves Ehouman"> 

    @hasSection('title')
        <title>@yield('title') | {{ config('app.name') }} | @yield('title') </title>
    @else
        <title>{{ config('app.name') }} | PHP Laravel Library Management App </title>
    @endif 
    
    <!-- CSS for this template -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/circular-std/style.css">
    <link rel="stylesheet" href="/assets/libs/css/style.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/assets/vendor/charts/chartist-bundle/chartist.css">
    <link rel="stylesheet" href="/assets/vendor/charts/morris-bundle/morris.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendor/charts/c3charts/c3.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">


    <link rel="stylesheet" type="text/css" href="/assets/vendor/datatables/css/dataTables.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/datatables/css/buttons.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/datatables/css/select.bootstrap4.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendor/datatables/css/fixedHeader.bootstrap4.css">



</head>



<body>
  
    <div class="dashboard-main-wrapper">

        <!-- navbar -->
        @include('includes.navbar')
        <!-- navbar -->

        <!-- left sidebar -->
        @include('includes.left_sidebar')
        <!-- left sidebar -->

        <!-- the content -->
        <div class="dashboard-wrapper">
            @include('includes.flash_messages')
            @yield('content')

             <!-- right sidebar -->
            @include('includes.footer')
            <!-- right sidebar -->

        </div>
        <!-- the content -->

       

       
    </div>
 

 
   <!-- jquery 3.3.1 -->
   <script src="/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
   <!-- bootstap bundle js -->
   <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

   <!-- slimscroll js -->
   <script src="/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
   <!-- main js -->
   <script src="/assets/libs/js/main-js.js"></script>
   <!-- chart chartist js -->
   
   
   <script src="/assets/libs/js/dashboard-ecommerce.js"></script>

   <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="/assets/vendor/datatables/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/vendor/datatables/js/data-table.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/rowgroup/1.0.4/js/dataTables.rowGroup.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>


    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const deleteUrl = this.getAttribute('data-url');
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.setAttribute('action', deleteUrl);
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
        });
    
        document.querySelector('[data-bs-dismiss="modal"]').addEventListener('click', function () {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.hide();
        });
    </script>
    
</body>



</html>
