<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reserva\ImplementoRequest;
use App\Models\Bloques;
use Illuminate\Http\Request;
use App\Models\Implemento;
use App\Models\Ubicacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class ImplementoController extends Controller
{

    public function post_registrar(ImplementoRequest $request){
        $sql=true;
        try {
            //OBTENGO EL ID DE LA UBICACION QUE SE SELECIONÓ
            $nom_ubi = $request->input('nombre_ubicacion');
            $ubi = DB::table("ubicaciones")->where('nombre_ubicacion', $nom_ubi)->first();
            $id_ubicacion = $ubi->id;

            //OBTENGO EL ID DEL ESTADO DISPONIBLE
            $estado = DB::table("estado_reservas")
            ->where('nombre_estado',"Disponible")
            ->first();
            $id_estado=$estado->id;

            DB::table("reservas")->insert([
                "nombre" => $request->nombre,
                "estado_reserva_id" => $id_estado,
                "ubicacione_id" => $id_ubicacion
            ]);
            $id_reserva = DB::getPdo()->lastInsertId();

            DB::table("implementos")->insert([
                "reserva_id" => $id_reserva,
                "cantidad" => $request->cantidad,
            ]);
            return back()->with("success","Implemento registrado correctamente");
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un error al guardar el registro!');
        }
    }
    public function get_registrar(){
        $ubicacionesDeportivas = Ubicacion::where('categoria', 'deportivo')->whereNotIn('nombre_ubicacion',['aire libre'])->get();
        $id_bloque=1;
        return view('implemento.registrar', compact('ubicacionesDeportivas'));
    }

    public function get_reservar(){
        $bloquesDisponibles = Bloques::all();
        return view('implemento.reservar',compact('bloquesDisponibles'));
    }

    public function get_reservar_filtrado(){
        try {
            $datos = session('datos');
            $implementosDisponible = $datos['implementosDisponible'];
            $id_bloque = $datos['id_bloque'];
            $fecha_reserva = $datos['fecha_reserva'];
            return view('Implemento.reservar_filtrado', compact('implementosDisponible', 'id_bloque', 'fecha_reserva'));

        } catch (\Throwable $th) {
            //throw $th;
            // return back()->with('error', 'Salió mal');
            return redirect()->route('implemento_reservar');
        }

        
    }

    public function get_modificarcantidad_agregar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->get(['reservas.nombre','implementos.cantidad','implementos.id']);
          
        //dd($implementosDisponibles);
        return view('implemento.agregar',compact('implementosDisponibles'));
    }

    public function get_modificarcantidad_eliminar(){
        $implementosDisponibles = Implemento::join('reservas','implementos.reserva_id','=','reservas.id')->where('implementos.cantidad','>',0)->get(['reservas.nombre','implementos.cantidad','implementos.id']);
        return view('implemento.eliminar',compact('implementosDisponibles'));
    }

    public function post_reservar(Request $request){

        try {
            //OBTENGO EL ID DEL BLOQUE QUE SE SELECIONÓ
            $id_bloque=$request->input('bloques');

            //OBTENER FECHA DE LA RESERVA
            $fecha_reserva=$request->input('fecha');

            // Esta wea no funciona
            $consulta = "SELECT * FROM implementos INNER JOIN reservas ON reservas.id = implementos.reserva_id AND reserva_id NOT IN ( SELECT reservas.id FROM instancia_reservas INNER JOIN reservas ON instancia_reservas.reserva_id = reservas.id INNER JOIN implementos ON instancia_reservas.reserva_id = implementos.reserva_id WHERE instancia_reservas.fecha_reserva= ? AND instancia_reservas.bloque_id = ? AND implementos.cantidad <= ( SELECT COUNT(*) FROM instancia_reservas ir WHERE ir.fecha_reserva = instancia_reservas.fecha_reserva AND ir.reserva_id = instancia_reservas.reserva_id AND ir.bloque_id = instancia_reservas.bloque_id) GROUP BY reservas.id)";

            $implementosDisponible=DB::select($consulta, [$fecha_reserva, $id_bloque]);
            $datos = ["implementosDisponible" => $implementosDisponible, 'id_bloque' => $id_bloque, 'fecha_reserva' => $fecha_reserva];
            return redirect()->route('implemento_reservar_filtrado')->with('datos', $datos);
        } catch (\Throwable $th) {
            return back()->with('error', 'Salió mal');
        }

    }

    public function post_reservar_filtrado(Request $request){
        try{
            $id_usuario= Auth::user()->id;
            $id_bloque=$request->input('bloque');
            $id_cancha = $request->input('seleccionImplemento');
            // $sala_estudio = DB::table("reservas")->find($id_sala_estudio); //Busco el registro
            $fecha_reserva=$request->input('fecha');
            DB::table("instancia_reservas")->insert([
                "fecha_reserva" => $fecha_reserva,
                "reserva_id" => $id_cancha,
                "user_id" => $id_usuario,
                "bloque_id" => $id_bloque,
            ]);

            $estado_instancia_reserva = DB::table("estado_instancia_reservas")->where('nombre_estado', "reservado")->first();
            $id_estado_instancia = $estado_instancia_reserva->id;

            DB::table("historial_instancia_reservas")->insert([
                "fecha_reserva"=>$fecha_reserva,
                "user_id"=>$id_usuario,
                "bloque_id"=>$id_bloque,
                "reserva_id"=>$id_estado_instancia,
            ]);

            return redirect()->route('implemento_reservar');
        }catch (\Throwable $th){
            return back()->with('error', '¡Hubo un error al reservar!');
        }
    }

    public function post_reservar_filtrado(Request $request){

    }
    
    public function update(Request $request, Implemento $implemento)
    {
        $request->validate([
            "cantidad" => 'required',
        ]);
        $implemento=Implemento::find($request->id);
        $request['cantidad']=intval($request->cantidad)+intval($implemento->cantidad);

        //dd($request);
        $implemento->update($request->all());
        return  back()->with('success','implementos updated successfully');
    }
    public function eliminar(Request $request, Implemento $implemento)
    {
        $request->validate([
            "cantidad" => 'required',
        ]);
        $implemento=Implemento::find($request->id);
        //dd($request);
        $request['cantidad']=intval($implemento->cantidad)-intval($request->cantidad);
        if($request['cantidad']<0){
            return  back()->with('error','No se puede eliminar más implementos de los que hay');
        }

        //dd($request);
        $implemento->update($request->all());
        return  back()->with('success','implementos updated successfully');
    }
    /*public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    } 
    */
    

    /* public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
  
        $product->update($request->all());
  
        return redirect()->route('products.index')->with('success','Product updated successfully');
    }*/
}
