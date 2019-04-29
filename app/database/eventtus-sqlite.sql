begin; 

PRAGMA foreign_keys=OFF; 

CREATE TABLE anexo( 
      id  INTEGER    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      tamanho text   NOT NULL  , 
      local text   NOT NULL  , 
      tipo text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE atividade( 
      id  INTEGER    NOT NULL  , 
      evento_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   , 
      descricao_en text   , 
      local_nome text   NOT NULL  , 
      local_geolocalizacao text   , 
      dt_inicio datetime   NOT NULL  , 
      dt_fim datetime   , 
 PRIMARY KEY (id),
FOREIGN KEY(evento_id) REFERENCES evento(id)); 

 CREATE TABLE atividade_interacao( 
      id  INTEGER    NOT NULL  , 
      dt_registro datetime   NOT NULL  , 
      mensagem text   NOT NULL  , 
      fl_aprovado text   , 
      atividade_id int   NOT NULL  , 
      pessoa_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE avaliacao_atividade( 
      id  INTEGER    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      estrelas double   NOT NULL  , 
      comentario text   , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(inscricao_atividade_id) REFERENCES inscricao_atividade(id)); 

 CREATE TABLE avaliacao_evento( 
      id  INTEGER    NOT NULL  , 
      inscricao_id int   NOT NULL  , 
      estrelas double   NOT NULL  , 
      comentario text   , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(inscricao_id) REFERENCES inscricao(id)); 

 CREATE TABLE evento( 
      id  INTEGER    NOT NULL  , 
      responsavel_id int   NOT NULL  , 
      nome text   NOT NULL  , 
      nome_en text   , 
      descricao text   NOT NULL  , 
      desricao_en text   , 
      banner text   , 
      logo text   , 
      cor text   , 
      dt_inicio datetime   NOT NULL  , 
      dt_fim datetime   , 
      contato_nome text   NOT NULL  , 
      contato_email text   NOT NULL  , 
      contato_telefone text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(responsavel_id) REFERENCES pessoa(id)); 

 CREATE TABLE inscricao( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      evento_id int   NOT NULL  , 
      codigo text   NOT NULL  , 
      dt_ativacao datetime   NOT NULL  , 
      dt_cancelamento datetime   , 
 PRIMARY KEY (id),
FOREIGN KEY(evento_id) REFERENCES evento(id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id)); 

 CREATE TABLE inscricao_atividade( 
      id  INTEGER    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      inscricao_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(inscricao_id) REFERENCES inscricao(id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE mensagem( 
      id  INTEGER    NOT NULL  , 
      inscricao_atividade_id int   NOT NULL  , 
      dt_registro datetime   NOT NULL  , 
      conteudo text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(inscricao_atividade_id) REFERENCES inscricao_atividade(id)); 

 CREATE TABLE notificacao_atividade( 
      id  INTEGER    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao datetime   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE notificacao_evento( 
      id  INTEGER    NOT NULL  , 
      evento_id int   NOT NULL  , 
      titulo text   NOT NULL  , 
      titulo_en text   , 
      conteudo text   NOT NULL  , 
      conteudo_en text   , 
      logo text   , 
      dt_notificacao datetime   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(evento_id) REFERENCES evento(id)); 

 CREATE TABLE opcao( 
      id  INTEGER    NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
      valor text   NOT NULL  , 
 PRIMARY KEY (id)); 

 CREATE TABLE pergunta( 
      id  INTEGER    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      descricao text   NOT NULL  , 
      descricao_en text   , 
 PRIMARY KEY (id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE pergunta_opcao( 
      id  INTEGER    NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(opcao_id) REFERENCES opcao(id),
FOREIGN KEY(pergunta_id) REFERENCES pergunta(id)); 

 CREATE TABLE pessoa( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
      email text   NOT NULL  , 
      email2 text   , 
      email3 text   , 
      foto text   , 
      token text   , 
      idioma text   , 
 PRIMARY KEY (id)); 

 CREATE TABLE responsaveis_atividade( 
      id  INTEGER    NOT NULL  , 
      atividade_id int   NOT NULL  , 
      usuario_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(atividade_id) REFERENCES atividade(id)); 

 CREATE TABLE resposta_pergunta( 
      id  INTEGER    NOT NULL  , 
      atividade_inscricao_id int   NOT NULL  , 
      opcao_id int   NOT NULL  , 
      pergunta_id int   NOT NULL  , 
      dt_registro datetime   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(pergunta_id) REFERENCES pergunta(id),
FOREIGN KEY(opcao_id) REFERENCES opcao(id),
FOREIGN KEY(atividade_inscricao_id) REFERENCES inscricao_atividade(id)); 

  
 commit;