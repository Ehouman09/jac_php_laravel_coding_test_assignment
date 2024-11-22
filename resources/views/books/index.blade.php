@extends('layouts.dashboard_layout')

@section('title', __('book.books'))

@section('content')

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this book?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <!-- The form is submitted once confirmed -->
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-ecommerce">
    <div class="container-fluid dashboard-content">

        <!-- PAGE-HEADER -->
        <div class="page-header">
            <div class="ms-auto pageheader-btn" style="text-align: end">
                <a href="{{route('books.create')}}" class="btn btn-danger btn-icon text-white me-2">
                    <span></span> Add New Book
                </a>
            </div>
        </div>
        <!-- PAGE-HEADER END -->

        <div class="ecommerce-widget">
            <div class="row">
                <!-- Sales Card -->
                <div class="col-lg-4 col-md-4  col-12">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Books</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="ps-3">
                                    <h1> {{ $total_books }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Sales Card -->

                <!-- Revenue Card -->
                <div class="col-lg-4 col-md-4  col-12">
                    <div class="card info-card revenue-card">
                        <div class="card-body">
                            <h5 class="card-title">My recorded Books</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="ps-3">
                                    <h1> {{ $total_user_books }} </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Revenue Card -->

                <!-- Customers Card -->
                <div class="col-lg-4 col-md-4  col-12">
                    <div class="card info-card missing-card">
                        <div class="card-body">
                            <h5 class="card-title">Book Categories</h5>
                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person-x"></i>
                                </div>
                                <div class="ps-3">
                                    <h1 class="fw-bold fs-6"> {{ $total_categories }} </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- End Customers Card -->
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Books List</h5>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="books-table" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Cover Image</th>
                                            <th>Title</th>
                                            <th>Category</th>
                                            <th>Author</th>
                                            <th>Publication Year</th>
                                            <th>Registration Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $book)
                                            <tr>
                                                <td class="text-center">
                                                    @if($book->cover_image)
                                                        <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" width="50">
                                                    @else
                                                        <img src="{{ asset('storage/book_covers/default-cover-image.png') }}" alt="Default Cover Image" width="50">
                                                    @endif
                                                </td>
                                                <td>{{ $book->title }}</td>
                                                <td>{{ $book->category?->name }}</td>
                                                <td>{{ $book->author }}</td>
                                                <td>{{ $book->publication_year }}</td>
                                                <td>{{ $book->created_at->format('Y-m-d') }}</td>
                                                <td>
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('books.edit', $book->id) }}" class="btn btn-primary btn-sm rounded-11" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                        <i class="fas fa-edit text-white"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <button type="button" class="btn btn-danger btn-sm rounded-11 delete-btn" data-url="{{ route('books.destroy', $book->id) }}" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                        <i class="fas fa-trash-alt text-white"></i>
                                                    </button>
                                                 </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Let add the pagination links here by using Bootstrap   -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $books->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- ============================ -->

@endsection
