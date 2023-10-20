<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class StudentController extends Controller
{
    protected $fileName;

    /**
     *     @OA\Get(
     *     path="/api/students",
     *     summary="List",
     *     tags={"Get"},
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\jsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *             @OA\Property(property="name", type="string", example="mate"),
     *             @OA\Property(property="email", type="string", example="mate@hasa.com"),
     *             @OA\Property(property="phone", type="string", example="1234567899"),
     *             @OA\Property(property="img", type="string", format="jpeg, png, jpg, gif", example="https://example.com/image.jpg"),
     *            )),
     *           ),
     *        ),
     *    ),
     * ),
     */

    public function index()
    {
        $students = Student::all();
        if($students->count( )> 0){

            return response()->json([
                'status' => 200,
                'students' => $students
            ],200);
        }else{

        return response()->json([
            'status' => 404,
            'message' => 'No records found'
        ],404);

        
        }
    }  
    

    /**
     * 
     * @OA\Post(
     * path="/api/students",
     * summary="Create Student",
     * tags={"Post"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="phone", type="string"),
     *               @OA\Property(
     *                     property="img",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *            ),
     *        ),
     *    ),
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\jsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="name", type="string", example="mate"),
     *             @OA\Property(property="email", type="string", example="mate@hasa.com"),
     *             @OA\Property(property="phone", type="string", example="1234567899"),
     *             @OA\Property(property="img", type="string", format="jpeg, png, jpg, gif", example="https://example.com/image.jpg"),
     *           
     *         ),
     *     ),
     * ),
     * 
     * 
     */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        if($request->hasFile('img')){
            $image = $request->file('img');
            $this->fileName = time() . '.' . $image->getClientOriginalExtension();
            
            $img = Image::make($image->getRealPath());
            $img->stream();

            Storage::disk('local')->put('images/' . $this->fileName, $img, 'public');
        }

        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);

        }else{
            $student = Student::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'img' => 'images'.'/'. $this->fileName

            ]);

            if($student) {
                return response()->json([
                    'status' =>200,
                    'message' => "Student Create Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' =>500,
                    'message' => "Something Went Wrong!"
                ],500);
            }
        }
    }
/**
     *     @OA\Get(
     *     path="/api/students/{id}",
     *     summary="List",
     *     tags={"Get"},
     * 
     *         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *      ),
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Ok",
     *         @OA\jsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *             @OA\Property(property="name", type="string", example="mate"),
     *             @OA\Property(property="email", type="string", example="mate@hasa.com"),
     *             @OA\Property(property="phone", type="string", example="1234567899"),
     *             @OA\Property(property="img", type="string", format="jpeg, png, jpg, gif", example="https://example.com/image.jpg"),
     *            )),
     *           ),
     *        ),
     *    ),
     * ),
 */
    public function show($id)
    {
        $student = Student::find($id);
        if($student){

            return response()->json([
                'status' => 200,
                'students' => $student
            ],200);

        }else{

            return response()->json([
                'status' => 404,
                'message' => 'No Such Student Found!'

            ],404);
        
        }

    }  
    /**
     *     @OA\Put(
     *     path="/api/students/{id}/edit",
     *     summary="Edit",
     *     tags={"Put"},
     * 
     *         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *      ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *              @OA\Schema(
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="phone", type="string")
     *            ),
     *         ),
     *      ),
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Student Update Successfully",
     *        ),
     *     @OA\Response(
     *         response="404",
     *         description="No Student Found!",
     *        ),
     * ),
     */

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
        ]);
        if($validator->fails()){

            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ],422);

        }else{

            $student = Student::find($id);
            
            if($student) {
                $student->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                ]);

                return response()->json([
                    'status' =>200,
                    'message' => "Student Update Successfully"
                ],200);
            }else{
                return response()->json([
                    'status' =>404,
                    'message' => "No Student Found!"
                ],404);
            }
        } 
    }

      /**
     *@OA\Delete(
     *     path="/api/students/{id}/delete",
     *     summary="Delete",
     *     tags={"delete"},
     * 
     *         @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *      ),
     * 
     *     @OA\Response(
     *         response="200",
     *         description="Student Deleted Successfully!",
     *              
     *         ),
     * 
     *     @OA\Response(
     *         response="404",
     *         description="No Student Found!",
     *              
     *       ),
     * ),
     */  

    public function destroy($id)
    {
        $student = Student::find($id);
        if($student){

            $student->delete();
            return response()->json([
                'status' =>200,
                'message' => "Student Deleted Successfully!"
            ],200);
        }else{
            return response()->json([
                'status' =>404,
                'message' => "No Student Found!"
            ],404);
        }
    }
}


