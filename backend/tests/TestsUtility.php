<?php

namespace Tests;


use App\Models\Area;
use App\Models\User;
use App\Models\OperatorDetail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;


class TestsUtility
{
    public function createAdmin() : Authenticatable|Model
    {
        return User::factory()->create(['role_id' => User::ROLE['admin']]);
    }

    public function createOperator() : Authenticatable|Model
    {
        return User::factory()->hasDetail(1)->create(['role_id' => User::ROLE['operator']]); // hasDetail --> magic method! (relationship)
    }

    public function userRequestAttributes() : array
    {
        $this->createAreas();

        return [
            'first_name' => 'Giovannino',
            'last_name' => 'Rossi',
            'phone' => '+39 999.999999999',
            'email'=>'giovannino@giovannino.com',
            'password'=>'password',
            'password_confirmation' => 'password',
            'areas' => 'Lombardia, Roma (Provincia), Piemonte'
        ];
    }

    public function createAreas() : void
    {
        Area::factory()->create(['city' => 'a', 'prov_name' => 'Milano', 'region_name' => 'Lombardia']);
        Area::factory()->create(['city' => 'b', 'prov_name' => 'Monza e Brianza', 'region_name' => 'Lombardia']);
        Area::factory()->create(['city' => 'c', 'prov_name' => 'Vercelli', 'region_name' => 'Piemonte']);
        Area::factory()->create(['city' => 'd', 'prov_name' => 'Vercelli', 'region_name' => 'Piemonte']); // test groupBy
        Area::factory()->create(['city' => 'e', 'prov_name' => 'Roma', 'region_name' => 'Lazio']);
        Area::factory()->create(['city' => '-', 'prov_name' => 'Estero', 'region_name' => 'Estero']);
        Area::factory()->create(['city' => '-', 'prov_name' => 'Tutta Italia', 'region_name' => 'Tutta Italia']);
    }

    public function leadRequestAttributes() : array
    {
        return [
            'services_ids' => '1, 2, 3, 4',
            'name' => 'Pippo',
            'surname' => 'Rossi',
            'email' => 'pippo@rossi.com',
            'phone' => '+39 556 6598778',
            'area' => 'City Test, MI, Lombardia',
            'description' => 'Lorem Ipsum, Lorem Ipsum, Lorem Ipsum, Lorem Ipsum, ',
        ];
    }
}
