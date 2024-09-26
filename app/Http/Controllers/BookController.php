<?php
/*
   PAGINATION_COUNT      => this CONSTANT in Helpers folder in formatBytes.php
   validtaor_Book()      => this Function in Helpers folder in Books.php
   formatBytes()         => this Function in Helpers folder in formateByte.php
   save_files_books()    => this Function in Helpers folder in Books.php
*/

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\validator;
use App\Http\Requests\Book_Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Crypt;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // https://example.com?like=Books.name,[value] | https://example.com?category_id=[value] | https://example.com?author_id=[value] | https://example.com?accepted=[value]
    {
        if(auth()->check() and auth()->user()->user_type_id==1){
        $Books = Book::Join_With_3tables()->Select_Book_With_category_name()
        ->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($Books, 200);
        }
        else{
        $Books = Book::Join_With_3tables()->Select_Book_With_category_name()->where('accepted','=',true)
        ->filter()->inRandomOrder()->paginate(PAGINATION_COUNT);
        return response()->json($Books, 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Book_Request $request)
    {
        // the formatBytes() Function in Helpers folder return the format size
        $size_Book = formatBytes($request->file_path->getSize());

        if (is_null($request->book_aueditiondio)) {
            $size_audio_Book = null;
        } else {
            $size_audio_Book = formatBytes($request->book_audio->getSize());
        }

        $image_path = save_files_books($request->image,'image');
        $book_path = save_files_books($request->file_path,$request->name);
        $audio_book_path = save_files_books($request->book_audio,'audio');
        if(auth()->check() and auth()->user()->user_type_id==1){
            $accepted=1;
        }
        else{
            $accepted=0;
        }
        $data = Book::create([
            'name' => $request->name,
            'Publisher_id' => $request->Publisher_id,
            'accepted' => $accepted,
            'category_id' => $request->category_id,
            'edition' => $request->edition,
            'description' => $request->description,
            'file_path' => $book_path,
            'image' => $image_path,
            'book_audio' => $audio_book_path,
            'size_Book' => $size_Book,
            'size_audio_Book' => $size_audio_Book,
        ]);

        return response()->json($data->id, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $book = book::findOrfail($id);
        $category_id = $book->category_id;
        if(auth()->check() and auth()->user()->user_type_id==1)
        {
            $books = DB::table('books')
            ->select('name', 'file_path', 'image','Publisher_id', 'created_at', 'updated_at')
            ->where('category_id', '=', $category_id)
            ->where('name', '<>', $book->name)
            ->get();
        }
        else
        {
            $books = DB::table('books')
            ->select('name', 'file_path', 'image','Publisher_id', 'created_at', 'updated_at')
            ->where('category_id', '=', $category_id)
            ->where('name', '<>', $book->name)
            ->where('accepted','=',true)
            ->get();
        }
        $author = DB::table('author_books')
        ->join('authors', 'authors.id', '=', 'author_books.author_id')
        ->select('authors.name')
        ->where('author_books.book_id', '=', $id)
        ->get();

        $categories = DB::table('categories')
        ->select('categories.name')
        ->where('categories.id', '=', $category_id)->get();

        $publishers = DB::table('publishers')
        ->select('publishers.name')
        ->where('publishers.id', '=', $book->Publisher_id)->get();

        $books = [
            [$book], [$books], [$author], [$categories], [$publishers],
        ];

        return response()->json($books, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $book = book::findOrfail($id);
        return response()->json($book, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        $id = Crypt::decrypt($id);
        $book = book::findOrfail($id);
        $validator=Validator::make($request->all(),[
        'name'=>['required','max:100','string','min:2',Rule::unique('books')->ignore($id)],
        'image'=>['required',Rule::when($request->hasFile('image'),[file::types(['jpeg','bmp','png','jpg'])->max(2048)]),Rule::when(is_string($request->image),'string')],
        'file_path'=>['required',Rule::when($request->hasFile('file_path'),[file::types(['pdf'])]),Rule::when(is_string($request->file_path),'string')],
        'Publisher_id'=>['nullable',Rule::when($request->Publisher_id,[Rule::exists('publishers', 'id')])],
        'edition'=>['string'],
        'description'=>['nullable','max:1000'],
        'category_id'=>['required', Rule::exists('categories', 'id')],
        'book_audio' =>['nullable',Rule::when($request->hasFile('book_audio'),[file::types(['mp3'])]),Rule::when(is_string($request->book_audio),'string')],
        'accepted'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $book_path = $book->file_path;
        $image_path = $book->image;
        $size_Book = $book->size_Book;
        $audio_book_path = $book->book_audio;
        $size_audio_Book = $book->size_audio_Book;

        if ($request->file_path != $book->file_path)
        {
            if(\File::exists($book_path)){
                \File::delete($book_path);
                }
            $book_path = save_files_books($request->file_path,$request->name);
            $size_Book = formatBytes(filesize($book_path));
        }

        if ($request->image != $book->image)
        {
            if(\File::exists($book->image)){
                \File::delete($book->image);
                }
            $image_path = save_files_books($request->image,'image');
        }

        if(is_null($request->book_audio)){
            if(\File::exists($book->book_audio)){
                \File::delete($book->book_audio);
            }
            $audio_book_path=null;
            $size_audio_Book=null;
        }
        elseif ($request->book_audio != $book->book_audio)
        {
            if(\File::exists($book->book_audio)){
                \File::delete($book->book_audio);
                }
            $audio_book_path = save_files_books($request->book_audio,'audio');
            $size_audio_Book = formatBytes(filesize($audio_book_path));
        }

        $book->update([
            'name' => $request->name,
            'Publisher_id' => $request->Publisher_id,
            'accepted' => $request->accepted,
            'category_id' => $request->category_id,
            'edition' => $request->edition,
            'description' => $request->description,
            'file_path' => $book_path,
            'image' => $image_path,
            'book_audio' => $audio_book_path,
            'size_Book' => $size_Book,
            'size_audio_Book' => $size_audio_Book,
        ]);

        return response()->json([
            'message'=>'Book successfully updated',
            'data'=>$book,
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $book = Book::findOrfail($id);
        if(\File::exists($book->file_path)){
            \File::delete($book->file_path);
            }
        if(\File::exists($book->image)){
            \File::delete($book->image);
            }
        if(\File::exists($book->book_audio)){
            \File::delete($book->book_audio);
            }
        $book->delete();
        return response()->json([
            'message'=>'Book successfully deleted',
            'data'=>$book,
            ], 200);
    }
}
