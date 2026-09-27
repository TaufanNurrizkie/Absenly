<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->only(['judul', 'penulis', 'konten']);
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(env('BERITA_IMG_PATH', public_path('img')), $filename);
            $data['gambar'] = $filename;
        }
        Berita::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan!'
        ]);
    }

    public function edit(Berita $berita)
    {
        return response()->json($berita);
    }

    public function update(Request $request, Berita $berita)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'konten' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->only(['judul', 'penulis', 'konten']);
        if ($request->hasFile('gambar')) {
            $imgPath = env('BERITA_IMG_PATH', public_path('img'));
            if ($berita->gambar && file_exists($imgPath . '/' . $berita->gambar)) {
                unlink($imgPath . '/' . $berita->gambar);
            }
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($imgPath, $filename);
            $data['gambar'] = $filename;
        }
        $berita->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diperbarui!'
        ]);
    }

    public function destroy(Berita $berita)
    {
        $imgPath = env('BERITA_IMG_PATH', public_path('img'));
        if ($berita->gambar && file_exists($imgPath . '/' . $berita->gambar)) {
            unlink($imgPath . '/' . $berita->gambar);
        }
        $berita->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus!'
        ]);
    }
}