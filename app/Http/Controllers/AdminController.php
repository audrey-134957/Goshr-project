<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUpdateAdmin;
use App\Models\Role;
use App\Models\User;

use App\Http\Requests\EditAdmin;
use App\Http\Requests\StoreAdmin;
use App\Notifications\SendEmailToAdminReferingToDeletingProfile;
use App\Notifications\SendEmailToAdminReferingToUpdatingPassword;
use App\Notifications\SendValidationMailToAdmin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class AdminController extends Controller
{

    /**
     * Show the form for edit the admin.
     * 
     * @param  string $adminFirstname | slug of the authenticated super-admin firstname
     * @param  string $adminName | slug of the authenticated super-admin's name
     * @return \Illuminate\Http\Response
     */
    public function edit($adminId)
    {

        $roles = Role::all();

        //je montre la view et lui passe l'admin.
        return view('admins.edit', [
            'adminId' => auth()->user()->id,
            'roles' => $roles
        ]);
    }

    /**
     * Update the admin in database.
     * @param  \Illuminate\Http\EditAdmin $request
     * @param  string $adminFirstname | slug of the authenticated super-admin firstname
     * @param  string $adminName | slug of the authenticated super-admin's name
     * @return \Illuminate\Http\Response
     */
    public function update(EditAdmin $request, $adminId)
    {
        // si un fichier image est contenu dans le champs image
        if ($request->avatar) {
            // je stocke le pseudonyme de l'utilisateur dans la variable
            $adminFolder = auth()->user()->username;
            //je stoke le chemin du dossier que je vais créer par la suite dans une variable
            $storagePath = 'avatars/admin/' . $adminFolder;
            // j'apelle fonction deleteOldAvatar() qui va se charger de supprimer l'ancienne photo de profil de l'utilisateur et qui va laisser place la nouvelle photo de profil
            $this->deleteOldUserAvatar();
            //si le dossier n'existe pas, je le créer
            if (!Storage::exists($storagePath)) {
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                $imagePath = $request->avatar->store($storagePath, 'public');
            }

            $new_name = 'avatar_' . gmdate('d_m_Y_') . uniqid()  . '.' . $request->avatar->getClientOriginalExtension();
            //je viens redimensionner mon image
            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(800, 800);

            //je vais stoker mon image
            $image->save();

            // je mets à jour le profil de l'admin
            auth()->user()->update(array_merge(
                //je viens passer en premier argument mon tableau $adminDatas
                $request->only('email', 'name', 'firstname'),
                //et en deuxième argument, la clé imgae qui nous emmenera vers $imagePath. Ainsi ce tableau viendra écrasera la valeur précédente concernant l'image
                ['avatar' => $imagePath]
            ));

            //si l'admin est bien sauvegardé
            if (!auth()->user()->save()) {
                //sinon je le redirige en lui signalant le.s erreur.s
                return redirect()->route('admin.edit', [
                    'adminId' => auth()->user()->id
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }
            // je le redirige sur son compte avec un status pour l'informer de la mise à jour de son profil
            return redirect()->route('admin.edit', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }

        // s'il il y a une saisie dans le champs password
        if ($request->password) {
            // si la saisie entrée pour le champs password correspond avec le mot de passe actuel de l'admin
            if (!Hash::check($request->password, auth()->user()->password)) {
                //en cas d'erreur, je redirige l'admin sur son compte en lui signalant qu'il y a une erreur
                return redirect()->route('admin.edit', [
                    'adminId' => auth()->user()->id
                ])->with('error', "Une erreur s'est produite lors du changement du mot de passe.");
            }
            // le nouveau mot de passe de l'admin sera haché et remplacera l'ancien mot de passe
            auth()->user()->password = bcrypt($request->password_new);
            auth()->user()->save();

            //je notifie l'admin de la mise à jour de mon mot de passe
            auth()->user()->notify(new SendEmailToAdminReferingToUpdatingPassword());
            //je redirige l'admin sur son compte en lui signalant que son mot de passse a été modifié
            return redirect()->route('admin.edit', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Le mot de passe a bien bien été modffié.');
        } else {
            //sinon je modifie entièrement l'admin.
            auth()->user()->name = $request->name;
            auth()->user()->firstname = $request->firtname;
            auth()->user()->role_id = $request->admin_role;
            auth()->user()->save();

            $adminUser->username = $request->username;
            $adminUser->email = $request->email;
            $adminUser->firstname = $request->firstname;
            $adminUser->name = $request->name;
            $adminUser->role_id = $request->admin_role;
            $adminUser->save();

            //si l'admin ou son profil est sauvé
            if (!auth()->user()->save() || !auth()->user()->profile->save()) {
                //je redirige l'admin vers la page d'édition en lui indiquant le.s erreur.s
                return redirect()->route('admin.edit', [
                    'adminId' => auth()->user()->id
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }
            //je redirige l'admin vers la page d'édition avec un status de confirmation
            return redirect()->route('admin.edit', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }

        if (auth()->user()->email !== $request->email) {
            auth()->user()->email = $request->email;
            auth()->user()->save();

            //si l'admin ou son profil est sauvé
            if (!auth()->user()->save() || !auth()->user()->profile->save()) {
                //je redirige l'admin vers la page d'édition en lui indiquant le.s erreur.s
                return redirect()->route('admin.edit', [
                    'adminId' => auth()->user()->id
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }
            //je redirige l'admin vers la page d'édition avec un status de confirmation
            return redirect()->route('admin.edit', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }
    }

    /**
     * Logout the admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //je déconnecte l'admin.
        auth()->logout();

        //je le redirige vers la page de connexion
        return redirect()->route('login.create');
    }


    /*********** Super Admin ***********/

    /**
     * Show the listing of the admins from the super admin account.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAdmins()
    {
        $admins = User::where('role_id', '!=', NULL)
            ->where('id', '!=', auth()->user()->id)
            ->get();

        if ($admins->count() > 1) {
            $text = 'administateurs';
            //sinon
        } else {
            $text = 'administrateur';
        }

        return view('admins.admins.index', [
            'admins' => $admins,
            'text' => $text
        ]);
    }

    /**
     * Show the admin creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAdmin()
    {

        $roles = Role::all();

        return view('admins.admins.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store the admin in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin(StoreAdmin $request)
    {

        /* Store new user */
        $admin = new User();
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->name = $request->name;
        $admin->firstname = $request->firstname;
        $admin->role_id = $request->admin_role;
        $admin->save();

        if (!$admin->save()) {
            return redirect()->route('admin.createAdmin', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de la création du compte administrateur.");
        }

        //je notifie un nouvel utilisateur
        $admin->notify(new SendValidationMailToAdmin($admin));
        // ... je redirige l'utilisateur vers la page d'accueil avec une notification sur le navigateur du succès de sa connexion.
        return redirect()->route('admin.indexAdmins', [
            'adminId' => auth()->user()->id
        ])->with('status', "Le compte a bien été créé.");
    }

    /**
     * Show the admin edition form from super admin account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $adminFirstname | slug of the authenticated super-admin firstname
     * @param  string $adminName | slug of the authenticated super-admin's name
     * @param  string $adminUserFirstname | slug of the admin user's firstname
     * @param  string $adminUserName | slug of the admin user's name
     * @return \Illuminate\Http\Response
     */
    public function editAdmin($adminId, $adminUserFirstname, $adminUserName)
    {
        $adminUser = User::where('firstname_slug', $adminUserFirstname)
            ->where('name_slug', $adminUserName)
            ->where('role_id', '!=', NULL)->firstOrFail();

        session(['adminUserId' => $adminUser->id]);

        $roles = Role::all();

        return view('admins.admins.edit', [
            'adminId' => auth()->user()->id,
            'adminUser' => $adminUser,
            'roles' => $roles
        ]);
    }

    /**
     * Update the admin in database.
     *
     * @param  \Illuminate\Http\AdminUpdateAdmin $request
     * @param  string $adminFirstname | slug of the authenticated super-admin firstname
     * @param  string $adminName | slug of the authenticated super-admin's name
     * @param  string $adminUserFirstname | slug of the admin user's firstname
     * @param  string $adminUserName | slug of the admin user's name
     * @return \Illuminate\Http\Response
     */
    public function updateAdmin(AdminUpdateAdmin $request, $adminId, $admin)
    {

        $adminUser = User::where('role_id', '!=', NULL)->findOrFail($admin);

        // si un fichier image est contenu dans le champs image
        if ($request->avatar) {
            // je stocke le pseudonyme de l'utilisateur dans la variable
            $adminFolder = $adminUser->username;
            //je stoke le chemin du dossier que je vais créer par la suite dans une variable
            $storagePath = 'avatars/admin/' . $adminFolder;
            // j'apelle fonction deleteOldAvatar() qui va se charger de supprimer l'ancienne photo de profil de l'utilisateur et qui va laisser place la nouvelle photo de profil
            $this->deleteOldUserAvatar();
            //si le dossier n'existe pas, je le créer
            if (!Storage::exists($storagePath)) {
                // je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                $imagePath = $request->avatar->store($storagePath, 'public');
            }

            $new_name = 'avatar_' . gmdate('d_m_Y_') . uniqid()  . '.' . $request->avatar->getClientOriginalExtension();
            //je viens redimensionner mon image
            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(800, 800);


            //je vais stoker mon image
            $image->save();

            // je mets à jour le profil de l'admin
            $adminUser->update(array_merge(
                //je viens passer en premier argument mon tableau $adminDatas
                $request->only('username', 'email', 'name', 'firstname', 'admin_role'),
                //et en deuxième argument, la clé imgae qui nous emmenera vers $imagePath. Ainsi ce tableau viendra écrasera la valeur précédente concernant l'image
                ['avatar' => $imagePath]
            ));


            //si l'admin est bien sauvegardé
            if (!$adminUser->save()) {
                //sinon je le redirige en lui signalant le.s erreur.s
                return redirect()->route('admin.editAdmin', [
                    'adminId' => auth()->user()->id
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }

            // je le redirige sur son compte avec un status pour l'informer de la mise à jour de son profil
            return redirect()->route('admin.editAdmin', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }

        // s'il il y a une saisie dans le champs password
        if ($request->password) {

            if (!Hash::check($request->password, $adminUser->password)) {
                //en cas d'erreur, je redirige l'admin sur son compte en lui signalant qu'il y a une erreur
                return redirect()->route('admin.editAdmin', [
                    'adminId' => auth()->user()->id
                ])->with('error', "Une erreur s'est produite lors du changement du mot de passe.");
            }
            // si la saisie entrée pour le champs password correspond avec le mot de passe actuel de l'admin
            // le nouveau mot de passe de l'admin sera haché et remplacera l'ancien mot de passe
            $adminUser->password = bcrypt($request->password_new);
            $adminUser->save();

            //je redirige l'admin sur son compte en lui signalant que son mot de passse a été modifié
            if (!$adminUser->save()) {
                //en cas d'erreur, je redirige l'admin sur son compte en lui signalant qu'il y a une erreur
                return redirect()->route('admin.editAdmin', [
                    'adminId' => auth()->user()->id
                ])->with('error', "Une erreur s'est produite lors du changement du mot de passe.");
            }

            //je notifie l'admin de la mise à jour de mon mot de passe
            $adminUser->notify(new SendEmailToAdminReferingToUpdatingPassword());

            return redirect()->route('admin.editAdmin', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Le mot de passe a bien bien été modffié.');

        } else {
            $adminUser->email = $request->email;
            $adminUser->firstname = $request->firstname;
            $adminUser->name = $request->name;
            $adminUser->role_id = $request->admin_role;
            $adminUser->save();

            //si l'admin ou son profil est sauvé
            if (!$adminUser->save()) {
                //je redirige l'admin vers la page d'édition en lui indiquant le.s erreur.s
                return redirect()->route('admin.editAdmin', [
                    'adminId' => auth()->user()->id
                ])->with('error', 'Il y a eu une erreur lors la modification du compte.');
            }
            //je redirige l'admin vers la page d'édition avec un status de confirmation
            return redirect()->route('admin.editAdmin', [
                'adminId' => auth()->user()->id
            ])->with('status', 'Les changements ont bien été pris en compte.');
        }
    }

    /**
     * Delete the admin in database.
     *
     * @param  string $adminFirstname | slug of the authenticated super-admin firstname
     * @param  string $adminName | slug of the authenticated super-admin's name
     * @param  string $adminUserFirstname | slug of the admin user's firstname
     * @param  string $adminUserName | slug of the admin user's name
     * @return \Illuminate\Http\Response
     */
    public function destroyAdmin($adminId, $admin)
    {
        //je trouve l'utilisateur
        $adminUser = User::where('role_id', '!=', NULL)->findOrFail($admin);

        // j'autorise l'action de pouvoir supprimer le profil uniquement à l'utilisateur connecté.
        // $this->authorize('delete', $);
        //si l'utilisateur existe
        if (!$adminUser) {
            //je redirige l'utilisateur vers la home avec un message lui confirmation la suppression de son compte.
            return redirect()->route('admin.editAdmin', [
                'adminId' => auth()->user()->id
            ])->with('error', "Ce compte n'a pas été trouvé.");
        }
        //je notifie l'utilisateur que son compte est supprimé
        $adminUser->notify(new SendEmailToAdminReferingToDeletingProfile($adminUser));
        //je supprime l'utilisateur
        $adminUser->delete();
        //je redirige l'utilisateur vers la home avec un message lui confirmation la suppression de son compte.
        return redirect()->route('admin.indexAdmins', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le compte a bien été supprimé.');
    }


    /**
     * Delete the old UserAvatar();
     */
    protected function deleteOldUserAvatar()
    {
        // si il existe l'ancien avatar de l'utilisateur
        if (auth()->user()->avatar) {
            //je le supprime
            Storage::delete('public/' . auth()->user()->avatar);
        }
    }
}
