<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $pharmacies = Pharmacy::all();
        return view("profiles.edit",["pharmacies" => $pharmacies]);
    }

    public function update(UpdateProfileRequest $request, User $profile)
    {
        $data = $request->validated();

        // Si no se envió contraseña, la quitamos del array para que no se actualice (ni se re-encripte)
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($request->hasFile("avatar_image")) {
            // Eliminar imagen anterior si existe
            if ($profile->avatar_image && Storage::disk("avatars")->exists($profile->avatar_image)) {
                Storage::disk("avatars")->delete($profile->avatar_image);
            }
            
            // Guardar nueva imagen
            $data["avatar_image"] = $request->file("avatar_image")
                ->store('', ["disk" => "avatars"]);
        }

        $profile->update($data);

        return redirect()->route("index")->with('success', 'Perfil actualizado correctamente');
    }
}
