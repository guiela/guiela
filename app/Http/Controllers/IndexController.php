<?php

namespace App\Http\Controllers;

use App\Services\CsvService;
use Illuminate\Http\Request;
use App\Http\Requests\CompareRequest;
use App\Http\Requests\ConversionRequest;

/**
 * IndexController class
 * 
 * @package App\Http\Controllers
 * @author Nascent Africa <nascent.afrique@gmail.com>
 */
class IndexController extends Controller
{
    protected $service;
    
    /**
     * IndexController constructor
     *
     * @param CsvService $service
     */
    public function __construct(CsvService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = auth()->check() ? new UserResource(auth()->user()) : null;

        return view('index')->with(['user' => $user]);
    }

    /**
     * Handle the incoming request.
     *
     * @param  ConversionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function extract(ConversionRequest $request)
    {
        $validated = $request->validated();

        try {
            $response = $this->service->extractDataFromFile($request->file, $request->delimiter, $request->file->getClientOriginalName());
        } catch (\Exception $exception) {
            $response = [
                'errors' => [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode() ?? '404'
                ]
            ];
        }

        return \response()->json($response);
    }

    /**
     * Handle the incoming request.
     *
     * @param CompareRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function compare(CompareRequest $request)
    {
        $data = $request->validated(); // Return valid data.

        $response = $this->service->setDomestic($data['domestic'])      // Set domestic data.
                                ->setForeign($data['foreign'])          // Set foreign data.
                                ->setIdentifiers($data['identifiers'])  // Set identifier.
                                ->setPairs($data['pairs'])              // Set pairs.
                                ->setMatcher($data['matcher'])          // Set matcher.
                                ->matchCSV()                            // Process user input.
                                ->getResult();                          // Retrieve result.

        return response()->json($response);
    }
}
