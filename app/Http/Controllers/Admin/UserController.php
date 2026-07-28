<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Rolí, které lze přiřazovat přes toto rozhraní. Admin je vyloučen záměrně — je jen jeden a nelze ho takto přidělit. */
    private const ASSIGNABLE_ROLES = [Role::USER_MANAGER, Role::CONTENT, Role::CLUB];

    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereIn('key', self::ASSIGNABLE_ROLES)->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles'    => 'array',
            'roles.*'  => Rule::in(self::ASSIGNABLE_ROLES),
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $roleIds = Role::whereIn('key', $data['roles'] ?? [])->pluck('id');
        $user->roles()->sync($roleIds);

        return redirect()->route('admin.users.index')->with('success', 'Uživatel byl přidán.');
    }

    public function edit(User $user)
    {
        $roles = Role::whereIn('key', self::ASSIGNABLE_ROLES)->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles'    => 'array',
            'roles.*'  => Rule::in(self::ASSIGNABLE_ROLES),
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $roleIds = Role::whereIn('key', $data['roles'] ?? [])->pluck('id')->toArray();

        // Admin roli nelze přes tento formulář ani přidat, ani odebrat — pokud ji
        // uživatel má, zůstává mu zachována bez ohledu na zaškrtnuté volby.
        if ($user->hasRole(Role::ADMIN)) {
            $roleIds[] = Role::where('key', Role::ADMIN)->value('id');
        }

        $user->roles()->sync($roleIds);

        return redirect()->route('admin.users.index')->with('success', 'Uživatel byl uložen.');
    }

    /** Rychlý reset hesla bez potřeby procházet celý editační formulář (a bez potřeby e-mailu). */
    public function resetPassword(Request $request, User $user)
    {
        if ($user->hasRole(Role::ADMIN)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Heslo uživatele s rolí Admin nelze měnit zde — použijte příkaz "php artisan admin:reset-password".');
        }

        $data = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return redirect()->route('admin.users.index')
            ->with('success', "Heslo pro {$user->name} bylo změněno.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->hasRole(Role::ADMIN)) {
            return redirect()->route('admin.users.index')->with('error', 'Uživatele s rolí Admin nelze smazat.');
        }

        if ($request->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Nemůžete smazat sami sebe.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Uživatel byl smazán.');
    }
}
