<?php
namespace App\Repositories;

use App\Helpers\FileUploader;
use App\Models\Module;

class ModuleRepository
{

    public function all()
    {
        return Module::orderBy('id', 'asc')
            ->with('user')
            ->get();
    }
    public function analytic()
    {
        $total = Module::with('user')->count('id');

        return [
            'total' => $total,
            'complete' => $total,
            'uncomplete' => 0
        ];
    }

    public function myModules()
    {
        return Module::orderBy('id', 'desc')
            ->with('user')
            ->where('user_id', auth()->guard()->user()->id)
            ->get();
    }


    public function search($keyword, $user = null)
    {
        return Module::when($user != null, function ($q) use ($user) {
            return $q->where('user_id', $user);
        })
            ->where('name', 'like', '%' . $keyword . '%')
            ->orWhere('description', 'like', '%' . $keyword . '%')
            ->orderBy('id', 'desc')
            ->with('user')
            ->get();
    }


    public function create(array $data)
    {
        $data['user_id'] = auth()->guard()->user()->id;

        // upload image file
        if (isset($data['image'])) {
            if ($data['image'] != null && $data['image'] != '' && !is_string($data['image'])) {
                $data['image'] = FileUploader::store('image', $data['image'], $data['name'], 'gallery/modules');
            }
        }
        return Module::create($data);
    }


    public function find($id)
    {
        return Module::with('user')->find($id);
    }


    public function update($id, array $data)
    {
        $product = Module::find($id);
        if ($product) {
            if (isset($data['image'])) {
                if ($data['image'] != null && $data['image'] != '' && !is_string($data['image'])) {
                    $data['image'] = FileUploader::update('image', $data['image'], $data['name'], 'gallery/modules', $product->image);
                }
            }
            # update product
            $product->update($data);

            return $this->find($product->id);
        }
    }


    public function delete($id)
    {
        $productres = Module::find($id);

        if ($productres) {
            FileUploader::delete('gallery/modules/' . $productres->image);
            $productres->delete();

            return true;
        }
        return false;
    }

}