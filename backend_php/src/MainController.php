<?php

class MainController {

    public function wrapInParagraphs($text) {
        $lines = explode("\n", trim($text));
        $wrappedText = "";

        foreach ($lines as $line) {
            if (!empty($line)) {
                $wrappedText .= "<div>" . htmlspecialchars($line) . "</div>";
            }
        }

        return $wrappedText;
    }


    public function bintangSatu($id) {
        $line = "";

        for ($i = 0; $i < $id; $i++) {
            $line .= "<p>";
            for ($j = 0; $j <= $i; $j++) {
                $line .= "*";
            }
            $line .= str_repeat("_", ($id - 1 - $i)); 
            $line .= "</p>";
        }
    
        return $line;
    }

    public function bintangDua($id) {
        $line = "";

        for ($i = $id; $i > 0; $i--) {
            $line .= "<p>";
            for ($j = $i; $j > 0; $j-- ) {
                $line .= "*";
            }
            $line .= "</p>";
        }
        return $line;
    }

    public function bintangTiga($id) {
        $line = "";
        $row = $id;

        for ($i = 0; $i < $row; $i++) {
            // Menggunakan &nbsp; untuk spasi
            $line .= "<p>";
            $line .= str_repeat("_", ($row - $i - 1)); // Menambahkan spasi
            $line .= str_repeat("*", ($i + 1)); // Menambahkan bintang
            $line .= "</p>"; // Menambah baris baru untuk setiap baris
        }

        return $line;
    }

    public function formatRupiah($value) {
        if(!is_numeric($value)) {
            return "Input harus berupa angka.";
        }

        $formattedRupiah = number_format($value, 0, ',', '.');
        $formattedRupiah = str_replace('.', ',', $formattedRupiah);
        return "Rp " . $formattedRupiah;
    }

}