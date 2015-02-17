<?php

class ItemsController extends Controller {

    function view($id = null,$name = null) {
        $this->set('title','Item '.$id);
        $this->Item = new Database();
        $this->Item->query('SELECT * FROM items WHERE id = :id');
        $this->Item->bind(':id', $id);
        $this->set('list',$this->Item->single());
    }

    function viewall() {
        $this->set('title','All Items');
        $this->Item = new Database();
        $this->Item->query('SELECT * FROM items');
        $this->set('list',$this->Item->resultset());
    }

    function add() {
        $item = isset($_POST['item']) ? $_POST['item'] : null;
        if ($item) {
            $this->set('title', 'Successfully added item');
            $this->set('msg', 'Successfully added item');
            $this->Item = new Database();
            $this->Item->query('INSERT INTO items (item_name) VALUES :item_name');
            $this->Item->bind(':item_name', $item);
            $this->Item->execute();
            $this->Item->lastInsertId();
        } else {
            $this->set('title', 'Failed to add item');
            $this->set('msg', 'Failed to add item');
        }
    }

    function delete($id = null) {
    }

}
