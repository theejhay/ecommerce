<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $data = file_get_contents(storage_path('app/public/data.json'));
        $dataObject = json_decode($data, true);
        return response()->json($dataObject);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request

     */
    public function store(Request $request)
    {
        $data = file_get_contents(storage_path('app/public/data.json'));
        $dataArray = json_decode($data, true);

        $newDataObject['id'] = $this->getNumberOfArrayObject($dataArray) + 1;
        $newDataObject['product_name'] = $request->product_name;
        $newDataObject['quantity'] = $request->quantity;
        $newDataObject['price'] = $request->price;
        $newDataObject['date'] = Carbon::now();
        $dataArray[] = $newDataObject;

        file_put_contents(storage_path('app/public/data.json'), json_encode($dataArray));

        return response()->json([
            'status' => true
        ]);
    }

    private function getNumberOfArrayObject(array $array = null)
    {
        $count = 0;

        if ($array == null)
            return 0;

        foreach ($array as $object) {
            ++$count;
        }

        return $count;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = file_get_contents(storage_path('app/public/data.json'));
        $dataArray = json_decode($data, true);

        $dataObject = [];

        foreach ($dataArray as $key => $value) {
            if ($value['id'] == $id) {
                $dataObject['id'] = $value['id'];
                $dataObject['product_name'] = $value['product_name'];
                $dataObject['quantity'] = $value['quantity'];
                $dataObject['price'] = $value['price'];
            }
        }

        return response()->json($dataObject);

    }


    public function update(Request $request, $id)
    {
        $data = file_get_contents(storage_path('app/public/data.json'));
        $dataArray = json_decode($data, true);

        foreach ($dataArray as $key => $value) {
            if ($value['id'] == $id) {
                $dataArray[$key]['product_name'] = $request->product_name;
                $dataArray[$key]['quantity'] = $request->quantity;
                $dataArray[$key]['price'] = $request->price;
            }
        }

        return response()->json([
            'status' => true
        ]);
    }


    public function destroy($id)
    {
        $data = file_get_contents(storage_path('app/public/data.json'));
        $dataArray = json_decode($data, true);


        foreach ($dataArray as $key => $value) {
            if ($value['id'] == $id) {
                unset($dataArray[$key]);
            }
        }

        file_put_contents(storage_path('app/public/data.json'), json_encode($dataArray));

        return response()->json([
            'status' => true
        ]);
    }
}
