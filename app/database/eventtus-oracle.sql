begin; 

CREATE TABLE anexo( 
      id number(10)    NOT NULL , 
      atividade_id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      tamanho CLOB    NOT NULL , 
      local CLOB    NOT NULL , 
      tipo CLOB    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE atividade( 
      id number(10)    NOT NULL , 
      evento_id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      nome_en CLOB   , 
      descricao CLOB   , 
      descricao_en CLOB   , 
      local_nome CLOB    NOT NULL , 
      local_geolocalizacao CLOB   , 
      dt_inicio timestamp(0)    NOT NULL , 
      dt_fim timestamp(0)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_atividade( 
      id number(10)    NOT NULL , 
      inscricao_atividade_id number(10)    NOT NULL , 
      estrelas binary_double    NOT NULL , 
      comentario CLOB   , 
      dt_registro timestamp(0)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE avaliacao_evento( 
      id number(10)    NOT NULL , 
      inscricao_id number(10)    NOT NULL , 
      estrelas binary_double    NOT NULL , 
      comentario CLOB   , 
      dt_registro timestamp(0)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE evento( 
      id number(10)    NOT NULL , 
      responsavel_id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      nome_en CLOB   , 
      descricao CLOB    NOT NULL , 
      desricao_en CLOB   , 
      banner CLOB   , 
      logo CLOB   , 
      cor CLOB   , 
      dt_inicio timestamp(0)    NOT NULL , 
      dt_fim timestamp(0)   , 
      contato_nome CLOB    NOT NULL , 
      contato_email CLOB    NOT NULL , 
      contato_telefone CLOB    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      evento_id number(10)    NOT NULL , 
      codigo CLOB    NOT NULL , 
      dt_ativacao timestamp(0)    NOT NULL , 
      dt_cancelamento timestamp(0)   , 
 PRIMARY KEY (id)); 

 CREATE TABLE inscricao_atividade( 
      id number(10)    NOT NULL , 
      atividade_id number(10)    NOT NULL , 
      inscricao_id number(10)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE mensagem( 
      id number(10)    NOT NULL , 
      inscricao_atividade_id number(10)    NOT NULL , 
      dt_registro timestamp(0)    NOT NULL , 
      conteudo CLOB    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_atividade( 
      id number(10)    NOT NULL , 
      atividade_id number(10)    NOT NULL , 
      titulo CLOB    NOT NULL , 
      titulo_en CLOB   , 
      conteudo CLOB    NOT NULL , 
      conteudo_en CLOB   , 
      logo CLOB   , 
      dt_notificacao timestamp(0)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE notificacao_evento( 
      id number(10)    NOT NULL , 
      evento_id number(10)    NOT NULL , 
      titulo CLOB    NOT NULL , 
      titulo_en CLOB   , 
      conteudo CLOB    NOT NULL , 
      conteudo_en CLOB   , 
      logo CLOB   , 
      dt_notificacao timestamp(0)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE opcao( 
      id number(10)    NOT NULL , 
      descricao CLOB    NOT NULL , 
      descricao_en CLOB   , 
      valor CLOB    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta( 
      id number(10)    NOT NULL , 
      atividade_id number(10)    NOT NULL , 
      descricao CLOB    NOT NULL , 
      descricao_en CLOB   , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta_opcao( 
      id number(10)    NOT NULL , 
      pergunta_id number(10)    NOT NULL , 
      opcao_id number(10)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE pessoa( 
      id number(10)    NOT NULL , 
      nome CLOB    NOT NULL , 
      email CLOB    NOT NULL , 
      email2 CLOB   , 
      email3 CLOB   , 
      foto CLOB   , 
      token CLOB   , 
      idioma CLOB   , 
 PRIMARY KEY (id)); 

 CREATE TABLE responsaveis_atividade( 
      id number(10)    NOT NULL , 
      atividade_id number(10)    NOT NULL , 
      usuario_id number(10)    NOT NULL , 
 PRIMARY KEY (id)); 

 CREATE TABLE resposta_pergunta( 
      id number(10)    NOT NULL , 
      atividade_inscricao_id number(10)    NOT NULL , 
      opcao_id number(10)    NOT NULL , 
      pergunta_id number(10)    NOT NULL , 
      dt_registro timestamp(0)    NOT NULL , 
 PRIMARY KEY (id)); 

  
 ALTER TABLE anexo ADD CONSTRAINT fk_anexo_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE atividade ADD CONSTRAINT fk_atividade_1 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE avaliacao_atividade ADD CONSTRAINT fk_avaliacao_atividade_1 FOREIGN KEY (inscricao_atividade_id) references inscricao_atividade(id); 
ALTER TABLE avaliacao_evento ADD CONSTRAINT fk_avaliacao_evento_1 FOREIGN KEY (inscricao_id) references inscricao(id); 
ALTER TABLE evento ADD CONSTRAINT fk_evento_1 FOREIGN KEY (responsavel_id) references pessoa(id); 
ALTER TABLE inscricao ADD CONSTRAINT fk_inscricao_2 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE inscricao ADD CONSTRAINT fk_inscricao_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE inscricao_atividade ADD CONSTRAINT fk_inscricao_atividade_2 FOREIGN KEY (inscricao_id) references inscricao(id); 
ALTER TABLE inscricao_atividade ADD CONSTRAINT fk_inscricao_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE mensagem ADD CONSTRAINT fk_mensagem_1 FOREIGN KEY (inscricao_atividade_id) references inscricao_atividade(id); 
ALTER TABLE notificacao_atividade ADD CONSTRAINT fk_notificacao_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE notificacao_evento ADD CONSTRAINT fk_notificacao_evento_1 FOREIGN KEY (evento_id) references evento(id); 
ALTER TABLE pergunta ADD CONSTRAINT fk_pergunta_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE pergunta_opcao ADD CONSTRAINT fk_pergunta_opcao_2 FOREIGN KEY (opcao_id) references opcao(id); 
ALTER TABLE pergunta_opcao ADD CONSTRAINT fk_pergunta_opcao_1 FOREIGN KEY (pergunta_id) references pergunta(id); 
ALTER TABLE responsaveis_atividade ADD CONSTRAINT fk_responsaveis_atividade_1 FOREIGN KEY (atividade_id) references atividade(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_3 FOREIGN KEY (pergunta_id) references pergunta(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_2 FOREIGN KEY (opcao_id) references opcao(id); 
ALTER TABLE resposta_pergunta ADD CONSTRAINT fk_resposta_pergunta_1 FOREIGN KEY (atividade_inscricao_id) references inscricao_atividade(id); 
 CREATE SEQUENCE anexo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER anexo_id_seq_tr 

BEFORE INSERT ON anexo FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT anexo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE atividade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER atividade_id_seq_tr 

BEFORE INSERT ON atividade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT atividade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE avaliacao_atividade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER avaliacao_atividade_id_seq_tr 

BEFORE INSERT ON avaliacao_atividade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT avaliacao_atividade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE avaliacao_evento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER avaliacao_evento_id_seq_tr 

BEFORE INSERT ON avaliacao_evento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT avaliacao_evento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE evento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER evento_id_seq_tr 

BEFORE INSERT ON evento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT evento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE inscricao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER inscricao_id_seq_tr 

BEFORE INSERT ON inscricao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT inscricao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE inscricao_atividade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER inscricao_atividade_id_seq_tr 

BEFORE INSERT ON inscricao_atividade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT inscricao_atividade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mensagem_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mensagem_id_seq_tr 

BEFORE INSERT ON mensagem FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT mensagem_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE notificacao_atividade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER notificacao_atividade_id_seq_tr 

BEFORE INSERT ON notificacao_atividade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT notificacao_atividade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE notificacao_evento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER notificacao_evento_id_seq_tr 

BEFORE INSERT ON notificacao_evento FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT notificacao_evento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE opcao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER opcao_id_seq_tr 

BEFORE INSERT ON opcao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT opcao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pergunta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pergunta_id_seq_tr 

BEFORE INSERT ON pergunta FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pergunta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pergunta_opcao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pergunta_opcao_id_seq_tr 

BEFORE INSERT ON pergunta_opcao FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pergunta_opcao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_id_seq_tr 

BEFORE INSERT ON pessoa FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT pessoa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE responsaveis_atividade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER responsaveis_atividade_id_seq_tr 

BEFORE INSERT ON responsaveis_atividade FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT responsaveis_atividade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE resposta_pergunta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER resposta_pergunta_id_seq_tr 

BEFORE INSERT ON resposta_pergunta FOR EACH ROW 

WHEN 

(NEW.id IS NULL) 

BEGIN 

SELECT resposta_pergunta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
  
 commit;