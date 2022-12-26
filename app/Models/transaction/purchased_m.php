<?php

namespace App\Models\transaction;

use App\Models\core_m;

class purchased_m extends core_m
{
    public function data()
    {
        $data = array();
        $data["message"] = "";
        //cek purchased
        if ($this->request->getVar("purchased_id")) {
            $purchasedd["purchased_id"] = $this->request->getVar("purchased_id");
        } else {
            $purchasedd["purchased_id"] = -1;
        }
            $purchasedd["store_id"] = session()->get("store_id");
        $us = $this->db
            ->table("purchased")
            ->getWhere($purchasedd);
        /* echo $this->db->getLastquery();
        die; */
        $larang = array("log_id", "id", "user_id", "action", "data", "purchased_id_dep", "trx_id", "trx_code");
        if ($us->getNumRows() > 0) {
            foreach ($us->getResult() as $purchased) {
                foreach ($this->db->getFieldNames('purchased') as $field) {
                    if (!in_array($field, $larang)) {
                        $data[$field] = $purchased->$field;
                    }
                }
            }
        } else {
            foreach ($this->db->getFieldNames('purchased') as $field) {
                $data[$field] = "";
            }
        }

        

        //delete
        if ($this->request->getPost("delete") == "OK") {                       
            $this->db
            ->table("purchased")
            ->delete(array("purchased_id" => $this->request->getPost("purchased_id"),"store_id" =>session()->get("store_id")));
            $data["message"] = "Delete Success";

             //update bill supplier
            $where1["supplier_id"] = $this->request->getGet("supplier_id");
            $builder=$this->db->table('supplier');
            $supplier_bill=$builder->getWhere($where1)->getRow()->supplier_bill;
            
            $input1["supplier_bill"] = $supplier_bill-$this->request->getPost("purchased_bill");
            $supplier=$builder->update($input1, $where1);
        }

        //insert
        if ($this->request->getPost("create") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'create' && $e != 'purchased_id' && $e != 'purchased_bill_before') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $input["store_id"] = session()->get("store_id");
            $input["purchase_id"] = $this->request->getGet("purchase_id");

            $builder = $this->db->table('purchased');
            $builder->insert($input);
            /* echo $this->db->getLastQuery();
            die; */
            $purchased_id = $this->db->insertID();

            $data["message"] = "Insert Data Success";

             //update bill supplier
            $where1["supplier_id"] = $this->request->getGet("supplier_id");
            $builder=$this->db->table('supplier');
            $supplier_bill=$builder->getWhere($where1)->getRow()->supplier_bill;
            
            $input1["supplier_bill"] = $supplier_bill+ $input["purchased_bill"];
            $supplier=$builder->update($input1, $where1);
        }
        //echo $_POST["create"];die;
        
        //update
        if ($this->request->getPost("change") == "OK") {
            foreach ($this->request->getPost() as $e => $f) {
                if ($e != 'change' && $e != 'purchased_picture' && $e != 'purchased_bill_before') {
                    $input[$e] = $this->request->getPost($e);
                }
            }
            $input["store_id"] = session()->get("store_id");
            $this->db->table('purchased')->update($input, array("purchased_id" => $this->request->getPost("purchased_id")));
            $data["message"] = "Update Success";
            //echo $this->db->last_query();die;

             //update bill supplier
            $where1["supplier_id"] = $this->request->getGet("supplier_id");
            $builder=$this->db->table('supplier');
            $supplier_bill=$builder->getWhere($where1)->getRow()->supplier_bill;
            
            $input1["supplier_bill"] = $supplier_bill-$this->request->getPost("purchased_bill_before")+$this->request->getPost("purchased_bill");
            $supplier=$builder->update($input1, $where1);
        }
        return $data;
    }
}