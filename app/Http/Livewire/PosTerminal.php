<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class PosTerminal extends Component
{
    public $search = '';
    public $cart = [];
    
    // Modal para crear producto nuevo
    public $showCreateProduct = false;
    public $newProductName = '';
    public $newProductPrice = '';
    public $newProductStock = '';

    public function getProductsProperty()
    {
        return Product::where('barbershop_id', auth()->user()->barbershop_id)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get();
    }

    public function addToCart($productId)
    {
        $product = Product::find($productId);
        if (!$product) return;

        if (isset($this->cart[$productId])) {
            // Si ya está, aumentamos cantidad si hay stock
            if ($this->cart[$productId]['quantity'] < $product->stock) {
                $this->cart[$productId]['quantity']++;
            } else {
                session()->flash('error', 'No hay suficiente stock de ' . $product->name);
            }
        } else {
            // Agregar nuevo si hay stock
            if ($product->stock > 0) {
                $this->cart[$productId] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'max_stock' => $product->stock
                ];
            } else {
                session()->flash('error', 'Producto agotado: ' . $product->name);
            }
        }
    }

    public function removeFromCart($productId)
    {
        if (isset($this->cart[$productId])) {
            unset($this->cart[$productId]);
        }
    }

    public function updateQuantity($productId, $action)
    {
        if (!isset($this->cart[$productId])) return;

        if ($action === 'increase') {
            if ($this->cart[$productId]['quantity'] < $this->cart[$productId]['max_stock']) {
                $this->cart[$productId]['quantity']++;
            }
        } elseif ($action === 'decrease') {
            if ($this->cart[$productId]['quantity'] > 1) {
                $this->cart[$productId]['quantity']--;
            } else {
                $this->removeFromCart($productId);
            }
        }
    }

    public function getTotalProperty()
    {
        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        DB::beginTransaction();
        try {
            // 1. Crear Venta principal
            $sale = Sale::create([
                'barbershop_id' => auth()->user()->barbershop_id,
                'user_id' => auth()->id(), // El barbero actual
                'total_amount' => $this->total
            ]);

            // 2. Crear los SaleItems y descontar stock
            foreach ($this->cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);

                // Descontar stock
                $product = Product::find($item['id']);
                $product->stock -= $item['quantity'];
                $product->save();
            }

            DB::commit();

            $this->cart = [];
            session()->flash('success', '¡Venta completada con éxito!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la venta.');
        }
    }

    // -- Crear Producto Rápido --
    public function saveProduct()
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
            'newProductPrice' => 'required|numeric|min:0',
            'newProductStock' => 'required|integer|min:0',
        ]);

        Product::create([
            'barbershop_id' => auth()->user()->barbershop_id,
            'name' => $this->newProductName,
            'price' => $this->newProductPrice,
            'stock' => $this->newProductStock,
        ]);

        $this->showCreateProduct = false;
        $this->reset(['newProductName', 'newProductPrice', 'newProductStock']);
        session()->flash('success', 'Producto agregado al inventario.');
    }

    public function render()
    {
        // Candado visual y lógico de acceso
        $plan = auth()->user()->barbershop->plan_type ?? 'basic';
        if ($plan === 'basic') {
            return view('livewire.locked-feature', [
                'featureName' => 'Punto de Venta (POS)',
                'requiredPlan' => 'Studio'
            ])->layout('layouts.app');
        }

        return view('livewire.pos-terminal', [
            'products' => $this->products,
            'total' => $this->total
        ]);
    }
}
