@extends('layouts.dashboard_layout')

@section('title', __('book.books'))

@section('content')

<div class="container pt-5 pb-5">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="section-block" id="basicform">
                <h3 class="section-title">Book Edition</h3>
            </div>
            <br>
            <div class="card pt-3">
                <div class="card-body">
                    <form action="{{ route('books.update', $book) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title</label>
                            <input id="title" name="title" type="text" value="{{ $book->title }}" class="form-control @error('title') is-invalid @enderror" autocomplete="off">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input id="author" name="author" value="{{  $book->author }}" type="text" placeholder="Author" class="form-control @error('author') is-invalid @enderror" autocomplete="off">
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Description" rows="3">{{  $book->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="publication_year">Publication Year</label>
                            <input id="publication_year" name="publication_year" type="number" value="{{  $book->publication_year }}" placeholder="Publication Year" class="form-control @error('publication_year') is-invalid @enderror" autocomplete="off">
                            @error('publication_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="cover_image" class="col-sm-2 col-form-label">Cover Image</label>
                            <input class="form-control @error('cover_image') is-invalid @enderror" name="cover_image" type="file" accept="image/*">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" width="100" class="mt-2">
                            @endif
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">-- Select a Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-sm-10 text-right">
                                <button type="submit" class="btn btn-danger">Update the book</button>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
