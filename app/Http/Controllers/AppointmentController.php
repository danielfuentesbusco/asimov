<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://5b32a2eb82407e001413f1d0.mockapi.io/b/']);		
		$response = $client->request('GET', 'appointments', []);
		$response = json_decode($response->getBody(), true);
		return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    
	    $request->date = $request->input('date');
	    $request->hour = $request->input('hour');
	    $request->contact = $request->input('contact');
	    
	    $request->date = explode("-", $request->date);
	    $request->date = $request->date[2].'-'.$request->date[1].'-'.$request->date[0];
	    $dayofweek = date('w', strtotime($request->date. ' ' . $request->hour));
	    if ($dayofweek == 0 || $dayofweek == 6){
		    return response()->json([
                'mensaje' => 'The schedule must be set for office hours (9 am to 6 pm Monday to Friday) all year long.'
            ], 200);
	    }
	    
	    $houroftheday = date('H', strtotime($request->date. ' ' . $request->hour));
	    if ($houroftheday < 9 && $houroftheday > 17){
		    // La última cita puede agendarse maximo a las 5pm, ya que dura una hora.
		    return response()->json([
                'mensaje' => 'The schedule must be set for office hours (9 am to 6 pm Monday to Friday) all year long.'
            ], 200);
	    }
	    
	    $client = new \GuzzleHttp\Client(['base_uri' => 'http://5b32a2eb82407e001413f1d0.mockapi.io/b/']);	
        
        // Se verifica si ya existe una cita en la fecha y hora ingresada
        $response = $client->request('GET', 'appointments', [
		    'query' => ['search' => 'KEY'.date("dmYHis",strtotime($request->date . ' ' .$request->hour))]
		]);

		$response = json_decode($response->getBody(), true);
		
		
		if(isset($response) && count($response)){
			return response()->json([
                'mensaje' => 'The system should not allow to book more than 1 appointment per hour.'
            ], 200);
		}
		
		if (!isset($request->date) || !isset($request->hour) || !isset($request->contact)){
			var_dump($request->date, $request->hour, $request->contact);
			die;
			return response()->json([
                'mensaje' => 'Death is very picky with its agenda, so every appointment must contain date, start time and contact information (like e-mail).'
            ], 200);
		}
		
		// You can only appointment of 1 hour long with Death, more would be pointless. Less would be too traumatic.
		// Esta validación se realiza de forma automática, solo se agendan citas de una hora de duración
        
        $data = array();
		$data["start"] = date("d-m-Y H:i:s",strtotime($request->date . ' ' .$request->hour));
		$data["end"] = date("d-m-Y H:i:s",strtotime($request->date . ' ' .$request->hour) + 60*60);
		$data["title"] = $request->contact;
		$data["datekey"] = 'KEY'.date("dmYHis",strtotime($request->date . ' ' .$request->hour));
		$data["backgroundColor"] = "#12CA6B";
		$data["textColor"] = "#FFF";
		
		$response = $client->request('POST', 'appointments', [
		    \GuzzleHttp\RequestOptions::JSON => $data
		]);
		
		$response = json_decode($response->getBody(), true);
		return response()->json([
            'mensaje' => 'Appointment created'
        ], 200);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
	    $client = new \GuzzleHttp\Client(['base_uri' => 'http://5b32a2eb82407e001413f1d0.mockapi.io/b/']);
        $response = $client->request('GET', 'appointments', [
		    'query' => ['search' => 'KEY'.date("dmYHis",strtotime($request->date . ' ' .$request->hour))]
		]);

		$response = json_decode($response->getBody(), true);
		
		if(isset($response) && count($response)){
			$data = array();
			$data["start"] = date("d-m-Y H:i:s",strtotime($request->date . ' ' .$request->hour));
			$data["end"] = date("d-m-Y H:i:s",strtotime($request->date . ' ' .$request->hour) + 60*60);
			$data["title"] = $request->contact;
			$data["datekey"] = 'KEY'.date("dmYHis",strtotime($request->date . ' ' .$request->hour));
			$data["backgroundColor"] = "#12CA6B";
			$data["textColor"] = "#FFF";

			$response = $client->request('PUT', 'appointments/'.$id, [
			    \GuzzleHttp\RequestOptions::JSON => $data
			]);
			return response()->json([
                'success' => 'Appointment updated.'
            ], 201);
		} else {
			return response()->json([
                'error' => 'Appointment not exists.'
            ], 404);
		} 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	   	$client = new \GuzzleHttp\Client(['base_uri' => 'http://5b32a2eb82407e001413f1d0.mockapi.io/b/']);
		$response = $client->request('DELETE', 'appointments/'.$id, []);
		return response()->json([
            'success' => 'Appointment deleted.'
        ], 201);
    }
    
    public function calendar()
    {
        return view('appointments');
    }
}
