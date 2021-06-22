<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });

    $app->get("/dokter/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM dokter";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(
            ["status" => "success", "data" => $result],
            200
        );
    });

    $app->get("/dokter/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM dokter WHERE id_dokter=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/dokter/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM dokter  WHERE nama_dokter  LIKE '%$keyword%' OR alamat_dokter 
        LIKE '%$keyword%' OR spesialis_dokter LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(
            ["status" => "success", "data" => $result],
            200
        );
    });

    $app->post("/dokter/", function (Request $request, Response $response) {
        $new_dokter = $request->getParsedBody();
        $sql = "INSERT INTO dokter  (nama_dokter, alamat_dokter, spesialis) VALUE (:nama_dokter, 
        :alamat_dokter, :spesialis)";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":nama_dokter" => $new_dokter["nama_dokter"],
            ":alamat_dokter" => $new_dokter["alamat_dokter"],
            ":spesialis" => $new_dokter["spesialis"]
        ];
        if ($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "berhasil menambahkan"],200);

        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->put("/dokter/{id_dokter}", function (Request $request, Response $response, $args) {
        
        $id_dokter = $args["id_dokter"];
        
        $new_dokter = $request->getParsedBody();
        $sql = "UPDATE dokter SET nama_dokter=:nama_dokter, alamat_dokter=:alamat_dokter, 
            spesialis=:spesialis WHERE id_dokter=:id_dokter";

        $stmt = $this->db->prepare($sql);
        $data = [
            ":id_dokter" => $id_dokter,
            ":nama_dokter" => $new_dokter["nama_dokter"],
            ":alamat_dokter" => $new_dokter["alamat_dokter"],
            ":spesialis" => $new_dokter["spesialis"]
        ];
        //$a = $stmt->execute($data);
        //print_r($sql);die;
         
        if ($stmt->execute($data))
            return $response->withJson(["status" => "success", "data" => "berhasil mengedit"],200);

        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    
    });

    $app->delete("/dokter/{id_dokter}", function (Request $request, Response $response, $args) {
        $id_dokter = $args["id_dokter"];
        $sql = "DELETE FROM dokter  WHERE id_dokter=:id_dokter";
        $stmt = $this->db->prepare($sql);

        $data = [
            ":id_dokter" => $id_dokter
        ];
        if ($stmt->execute($data))
            return $response->withJson(
                ["status" => "success", "data" => "sukses delete dokter"],
                200
            );
        return $response->withJson(["status" => "failed", "data" => "cancel "], 200);
    });


//tabel pasien
$app->get("/pasien/", function (Request $request, Response $response) {
    $sql = "SELECT * FROM pasien";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(
        ["status" => "success", "data" => $result],
        200
    );
});

$app->get("/pasien/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM pasien WHERE id_pasien=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson(["status" => "success", "data" => $result], 200);
});

$app->get("/pasien/search/", function (Request $request, Response $response, $args) {
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM pasien  WHERE nama_pasien  LIKE '%$keyword%' OR alamat_pasien 
    LIKE '%$keyword%'  OR keluhan like '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(
        ["status" => "success", "data" => $result],
        200
    );
});

$app->post("/pasien/", function (Request $request, Response $response) {
    $new_pasien = $request->getParsedBody();
    $sql = "INSERT INTO pasien  (id_pasien, nama_pasien, keluhan,  alamat_pasien) VALUE (:id_pasien, 
    :nama_pasien, :keluhan,  :alamat_pasien)";
    $stmt = $this->db->prepare($sql);
    $data = [
        ":id_pasien" => $new_pasien["id_pasien"],
        ":nama_pasien" => $new_pasien["nama_pasien"],
        ":keluhan" => $new_pasien["keluhan"],
        ":alamat_pasien" => $new_pasien["alamat_pasien"]
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "berhasil ditambahkan"],
            200);
        

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

$app->put("/pasien/{id_pasien}", function (Request $request, Response $response, $args) {
    $id_pasien = $args["id_pasien"];
    $new_pasien = $request->getParsedBody();
    $sql = "UPDATE pasien SET nama_pasien=:nama_pasien, keluhan=:keluhan, 
         alamat_pasien=:alamat_pasien WHERE id_pasien=:id_pasien";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id_pasien" => $id_pasien,
        ":nama_pasien" => $new_pasien["nama_pasien"],
        ":keluhan" => $new_pasien["keluhan"],
        ":alamat_pasien" => $new_pasien["alamat_pasien"],
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "data berhasil di edit"],
            200
        );
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

$app->delete("/pasien/{id_pasien}", function (Request $request, Response $response, $args) {
    $id_pasien = $args["id_pasien"];
    $sql = "DELETE FROM pasien  WHERE id_pasien=:id_pasien";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id_pasien" => $id_pasien 
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "delete data pasien"],
            200
        );
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
    
    //tabel pembayaran
$app->get("/pembayaran/", function (Request $request, Response $response) {
    $sql = "SELECT * FROM pembayaran ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson( ["status" => "success", "data" => $result], 200);
});


$app->get("/pembayaran/{id}", function (Request $request, Response $response, $args){
    $id = $args["id"];
    $sql = "SELECT * FROM pembayaran WHERE id_pembayaran=:id";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([":id" => $id]);
    $result = $stmt->fetch();
    return $response->withJson( ["status" => "success", "data" => $result], 200);
});

$app->get("/pembayaran/search/", function (Request $request, Response $response, $args) {
    $keyword = $request->getQueryParam("keyword");
    $sql = "SELECT * FROM pembayaran  WHERE id_petugas LIKE '%$keyword%' OR id_pasien
    LIKE '%$keyword%' OR harga LIKE '%$keyword%'";
    $stmt = $this->db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $response->withJson(
        ["status" => "success", "data" => $result],
        200
    );
});

$app->post("/pembayaran /", function (Request $request, Response $response) {
    $new_pembayaran = $request->getParsedBody();
    $sql = "INSERT INTO pembayaran  (id_petugas, id_pasien, harga) VALUE (:id_petugas, 
    :id_pasien, :harga)";
    $stmt = $this->db->prepare($sql);
    $data = [
        ":id_petugas" => $new_dokter["id_petugas"],
        ":id_pasien" => $new_dokter["id_pasien"],
        ":harga" => $new_dokter["harga"]
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "1"],
            200
        );

    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

$app->put("/pembayaran/{id_pembayaran}", function (Request $request, Response $response, $args) {
    $id_pembayaran = $args["id_pembayaran"];
    $new_pembayaran = $request->getParsedBody();
    $sql = "UPDATE pembayaran  SET id_petugas=:id_petugas, id_pasien=:id_pasien, harga:harga  WHERE id_pembayaran=:id_pembayaran";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id_pembayaran" => $id_pembayaran,
        ":id_petugas" => $new_pembayaran["id_petugas"],
        ":id_pasien" => $new_pembayaran["id_pasien"],
        ":harga" => $new_dokter["harga"],
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "1"],
            200
        );
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

$app->delete("/pembayaran/{id_pembayaran}", function (Request $request, Response $response, $args) {
    $id_pembayaran = $args["id_pembayaran"];
    $sql = "DELETE FROM pembayaran  WHERE id_pembayaran=:id_pembayaran";
    $stmt = $this->db->prepare($sql);

    $data = [
        ":id_pembayaran" => $id_pembayaran
    ];
    if ($stmt->execute($data))
        return $response->withJson(
            ["status" => "success", "data" => "1"],
            200
        );
    return $response->withJson(["status" => "failed", "data" => "0"], 200);
});

    //tabel pemtugas
    $app->get("/petugas/", function (Request $request, Response $response) {
        $sql = "SELECT * FROM petugas";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson( ["status" => "success", "data" => $result], 200);
    });
    
    
    $app->get("/petugas/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM petugas WHERE id_petugas=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson( ["status" => "success", "data" => $result], 200);
    });
    
    $app->get("/petugas/search/", function (Request $request, Response $response, $args) {
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM petugas  WHERE id_petugas LIKE '%$keyword%' OR nama_petugas
        LIKE '%$keyword%' OR alamat_petugas LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(
            ["status" => "success", "data" => $result],
            200
        );
    });
    
    $app->post("/petugas /", function (Request $request, Response $response) {
        $new_petugas = $request->getParsedBody();
        $sql = "INSERT INTO petugas (nama_petugas, alamat_petugas, jam_jaga) VALUE (:nama_petugas, 
        :alamat_petugas, :jam_jaga)";
        $stmt = $this->db->prepare($sql);
        $data = [
            ":nama_petugas" => $new_petugas["nama_petugas"],
            ":alamat_petugas" => $new_petugas["alamat_petugas"],
            ":jam_jaga" => $new_petugas["jam_jaga"]
        ];
        if ($stmt->execute($data))
            return $response->withJson(
                ["status" => "success", "data" => "1"],
                200
            );
    
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
    
    $app->put("/petugas/{id_petugas}", function (Request $request, Response $response, $args) {
        $id_petugas = $args["id_petugas"];
        $new_petugas = $request->getParsedBody();
        $sql = "UPDATE petugas  SET nama_petugas=:nama_petugas, alamat_petugas=:alamat_petugas, jam_jaga=:jam_jaga  WHERE id_petugas=:id_petugas";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":id_petugas" => $id_petugas,
            ":nama_petugas" => $new_petugas["nama_petugas"],
            ":alamat_petugas" => $new_petugas["alamat_petugas"],
            ":jam_jaga" => $new_petugas["jam_jaga"],
        ];
        if ($stmt->execute($data))
            return $response->withJson(
                ["status" => "success", "data" => "1"],
                200
            );
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
    
    $app->delete("/petugas/{id_petugas}", function (Request $request, Response $response, $args) {
        $id_petugas = $args["id_petugas"];
        $sql = "DELETE FROM petugas  WHERE id_petugas=:id_petugas";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":id_petugas" => $id_petugas
        ];
        if ($stmt->execute($data))
            return $response->withJson(
                ["status" => "success", "data" => "1"],
                200
            );
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};