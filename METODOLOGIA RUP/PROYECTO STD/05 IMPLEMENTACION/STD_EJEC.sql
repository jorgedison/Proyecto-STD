CREATE TABLE tb_std_alumnos (
  idtb_std_alumno INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  codigo_alumno INTEGER UNSIGNED NULL,
  nombre_alumno VARCHAR NULL,
  apellidopaterno_alumno VARCHAR NULL,
  apellidomaterno_alumno VARCHAR NULL,
  correo_electronico VARCHAR NULL,
  PRIMARY KEY(idtb_std_alumno)
);

CREATE TABLE tb_std_areas (
  idtb_std_area INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_area VARCHAR NULL,
  observacion_area INTEGER UNSIGNED NULL,
  PRIMARY KEY(idtb_std_area)
);

CREATE TABLE tb_std_estado_tramites (
  idtb_std_estado_tramite INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_estadotramite VARCHAR NULL,
  PRIMARY KEY(idtb_std_estado_tramite)
);

CREATE TABLE tb_std_expedientes (
  idtb_std_expediente INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  tb_std_tramites_idtb_std_tramite INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(idtb_std_expediente),
  INDEX tb_std_expedientes_FKIndex1(tb_std_tramites_idtb_std_tramite)
);

CREATE TABLE tb_std_perfil (
  idtb_std_perfil INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_perfil VARCHAR NULL,
  estado_perfil INTEGER UNSIGNED NULL,
  PRIMARY KEY(idtb_std_perfil)
);

CREATE TABLE tb_std_requisitos (
  idtb_std_requisito INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre_requisito VARCHAR NULL,
  costo_requisito DECIMAL NULL,
  fecha_registro DATETIME NULL,
  observacion_requisito VARCHAR NULL,
  PRIMARY KEY(idtb_std_requisito)
);

CREATE TABLE tb_std_requisitos_has_tb_std_tramites (
  tb_std_requisitos_idtb_std_requisito INTEGER UNSIGNED NOT NULL,
  tb_std_tramites_idtb_std_tramite INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY(tb_std_requisitos_idtb_std_requisito, tb_std_tramites_idtb_std_tramite),
  INDEX tb_std_requisitos_has_tb_std_tramites_FKIndex1(tb_std_requisitos_idtb_std_requisito),
  INDEX tb_std_requisitos_has_tb_std_tramites_FKIndex2(tb_std_tramites_idtb_std_tramite)
);

CREATE TABLE tb_std_tramites (
  idtb_std_tramite INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  tb_std_usuarios_idtb_std_usuario INTEGER UNSIGNED NOT NULL,
  tb_std_alumnos_idtb_std_alumno INTEGER UNSIGNED NOT NULL,
  tb_std_areas_idtb_std_area INTEGER UNSIGNED NOT NULL,
  tb_std_estado_tramites_idtb_std_estado_tramite INTEGER UNSIGNED NOT NULL,
  nombre_tramite VARCHAR NULL,
  fecha_registro DATETIME NULL,
  PRIMARY KEY(idtb_std_tramite),
  INDEX tb_std_tramites_FKIndex1(tb_std_estado_tramites_idtb_std_estado_tramite),
  INDEX tb_std_tramites_FKIndex2(tb_std_areas_idtb_std_area),
  INDEX tb_std_tramites_FKIndex3(tb_std_alumnos_idtb_std_alumno),
  INDEX tb_std_tramites_FKIndex4(tb_std_usuarios_idtb_std_usuario)
);

CREATE TABLE tb_std_usuarios (
  idtb_std_usuario INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  tb_std_perfil_idtb_std_perfil INTEGER UNSIGNED NOT NULL,
  nombre_usuario VARCHAR NULL,
  password_usuario VARCHAR NULL,
  correo_electronico VARCHAR NULL,
  fecha_registro DATETIME NULL,
  PRIMARY KEY(idtb_std_usuario),
  INDEX tb_std_usuarios_FKIndex1(tb_std_perfil_idtb_std_perfil)
);


