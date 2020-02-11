<?php

namespace App\Http\Controllers;

use App\DataUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataUploadController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Fetch files by Type or Id
     * @param  string $type  DataUpload type
     * @param  integer $id   DataUpload Id
     * @return object        DataUploads list, JSON
     */
    public function index($type, $id = null)
    {
        $model = new DataUpload();

        if (!is_null($id)) {
            $response = $model::findOrFail($id);
        } else {
            $records_per_page = ($type == 'video') ? 6 : 15;

            $files = $model::where('type', $type)
                ->where('user_id', Auth::id())
                ->orderBy('id', 'desc')->paginate($records_per_page);
you;
            $response = [
                'pagination' => [
                    'total' => $files->total(),
                    'per_page' => $files->perPage(),
                    'current_page' => $files->currentPage(),
                    'last_page' => $files->lastPage(),
                    'from' => $files->firstItem(),
                    'to' => $files->lastItem()
                ],
                'data' => $files
            ];
        }

        return response()->json($response);
    }

    /**
     * Upload new file and store it
     * @param  Request $request Request with form data: filename and file info
     * @return boolean          True if success, otherwise - false
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:files',
            'site' => 'required',
            'note' => 'required:max:255',
            'file' => 'required|file|mimes:' . DataUpload::getAllExtensions() . '|max:' . DataUpload::getMaxSize()
        ]);

        $data = new DataUpload();

        $uploaded_file = $request->file('file');
        $original_ext = $uploaded_file->getClientOriginalExtension();
        $type = $data->getType($original_ext);

        if ($data->upload($type, $uploaded_file, $request['name'], $original_ext))
        {
            return $data::create([
                'name' => $request->get('name'),
                'site' => $request->get('site'),
                'note' => $request->get('note'),
                'type' => $type,
                'extension' => $original_ext,
                'user_id' => Auth::id()
            ]);
        }

        return response()->json(false);
    }

    /**
     * Edit specific file
     * @param  integer  $id      DataUpload Id
     * @param  Request $request  Request with form data: filename
     * @return boolean           True if success, otherwise - false
     */
    public function edit($id, Request $request)
    {
        $data = DataUpload::where('id', $id)->where('user_id', Auth::id())->first();

        if ($data->name == $request['name']) {
            return response()->json(false);
        }

        $this->validate($request, [
            'title' => 'required|unique:files'
        ]);

        $old_filename = $data->getName($data->type, $data->title, $data->extension);
        $new_filename = $data->getName($request['type'], $request['name'], $request['extension']);

        if (Storage::disk('local')->exists($old_filename)) {
            if (Storage::disk('local')->move($old_filename, $new_filename)) {
                $data->name = $request['name'];
                return response()->json($data->save());
            }
        }

        return response()->json(false);
    }


    /**
     * Delete file from disk and database
     * @param  integer $id  DataUpload Id
     * @return boolean      True if success, otherwise - false
     */
    public function destroy($id)
    {
        $data = DataUpload::findOrFail($id);

        if (Storage::disk('local')->exists($data->getName($data->type, $data->name, $data->extension))) {
            if (Storage::disk('local')->delete($data->getName($data->type, $data->name, $data->extension))) {
                return response()->json($data->delete());
            }
        }

        return response()->json(false);
    }

}
