CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100),
  email VARCHAR(100),
  password VARCHAR(255),
  tipo_usuario ENUM('administrador', 'empleado', 'cliente'),
  fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


ALTER TABLE usuarios
MODIFY COLUMN tipo_usuario ENUM('administrador', 'empleado', 'cliente');





CREATE TABLE vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Identificador único del vehículo
    marca VARCHAR(50) NOT NULL,             -- Marca del vehículo (ej. Toyota, Ford)
    modelo VARCHAR(50) NOT NULL,            -- Modelo del vehículo (ej. Corolla, Mustang)
    matricula VARCHAR(20) UNIQUE NOT NULL,  -- Matrícula del vehículo (única)
    disponibilidad ENUM('Disponible', 'No disponible') DEFAULT 'Disponible',  -- Disponibilidad del vehículo
    tarifa DECIMAL(10, 2) NOT NULL,         -- Tarifa de alquiler por día o por unidad de tiempo
    estado ENUM('Excelente', 'Bueno', 'Regular', 'Malo') DEFAULT 'Bueno', -- Estado físico del vehículo
    color VARCHAR(30),                      -- Color del vehículo (opcional)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de registro del vehículo en el sistema
);

ALTER TABLE vehiculos
ADD tipo_vehiculo ENUM('auto', 'camioneta', 'camion','maquinaria');
/*RECIEN*/
ALTER TABLE vehiculos
ADD tipo_transmision ENUM('automatico', 'manual');
ALTER TABLE vehiculos
ADD tipo_combustible ENUM('extra', 'super','diesel','electricidad');
ALTER TABLE vehiculos
ADD cilindraje VARCHAR(50) NOT NULL;
ALTER TABLE vehiculos
ADD descripcion VARCHAR(50) NOT NULL;
ALTER TABLE vehiculos
CHANGE COLUMN matricula unica VARCHAR(20) UNIQUE NOT NULL;



ALTER TABLE vehiculos
ADD imagen VARCHAR(50);

CREATE TABLE tarifas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_vehiculo VARCHAR(50) NOT NULL,
    duracion_alquiler INT NOT NULL, -- En días
    temporada ENUM('alta', 'baja') NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);


CREATE TABLE alquileres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehiculo_id INT NOT NULL,               -- ID del vehículo alquilado
    usuario_id INT NOT NULL,                -- ID del usuario que realiza el alquiler
    fecha_inicio VARCHAR(50) NOT NULL,      -- Fecha de inicio del alquiler
    fecha_fin VARCHAR(50),                  -- Fecha de finalización del alquiler
    estado ENUM('Activo', 'Inactivo')   DEFAULT 'Activo',      -- Estado del alquiler
    FOREIGN KEY (vehiculo_id) REFERENCES vehiculos(id),  -- Clave foránea hacia la tabla vehiculos
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)     -- Clave foránea hacia la tabla usuarios
);


monto_tarifa DECIMAL(10, 2),
descripcion_devolucion VARCHAR(255),
fecha_devolucion VARCHAR(50),
devuelto ENUM('si', 'no'),
cargos_extra DECIMAL(10, 2),
monto_total DECIMAL(10, 2), 

ALTER TABLE alquileres
ADD COLUMN metodo_pago ENUM('tarjeta', 'efectivo') DEFAULT 'efectivo';



ALTER TABLE alquileres
ADD COLUMN monto_tarifa DECIMAL(10, 2),                 -- Tarifa base del alquiler
ADD COLUMN descripcion_devolucion VARCHAR(255),                 -- Descripción al devolver el vehículo
ADD COLUMN fecha_devolucion VARCHAR(50),                        -- Fecha en que se devolvió el vehículo
ADD COLUMN devuelto ENUM('si', 'no') DEFAULT 'no',              -- Indica si el vehículo fue devuelto
ADD COLUMN cargos_extra DECIMAL(10, 2) DEFAULT 0.00,            -- Cargos adicionales aplicados
ADD COLUMN monto_total DECIMAL(10, 2);                          -- Monto total del alquiler (tarifa + cargos extra)





INSERT INTO alquileres (vehiculo_id,usuario_id,fecha_inicio,fecha_fin,estado) VALUES (1,20,'2024-11-02','2024-11-03','Activo');