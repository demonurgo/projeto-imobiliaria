# Sistema de Gestão Imobiliária Fiscal

Sistema para gerenciamento de imobiliárias com foco em controle fiscal, particularmente para a geração da DIMOB (Declaração de Informações sobre Atividades Imobiliárias).

## Funcionalidades Principais

- Importação de XML de Notas Fiscais de Serviço Eletrônicas (NFS-e)
- Gestão de imóveis, inquilinos, tomadores e prestadores
- Emissão e controle de notas fiscais
- Geração de arquivo DIMOB compatível com a Receita Federal

## Tecnologias Utilizadas

- PHP 7.4+
- CodeIgniter 3.x
- MySQL 5.7+
- Bootstrap 5
- jQuery e DataTables

## Estrutura do Projeto

O sistema segue a estrutura padrão do CodeIgniter 3 com algumas adições:

- `application/core/MY_Model.php`: Classe base para modelos que implementa operações CRUD e em lote
- `application/helpers/batch_helper.php`: Helper para funcionalidades de operações em lote
- `application/helpers/auth_helper.php`: Helper para autenticação e autorização
- `docs/`: Documentação do sistema

## Atualizações Recentes

### Exclusão em Lote (Batch Delete)

Foi implementada a funcionalidade de exclusão em lote que permite selecionar múltiplos registros através de checkboxes para excluí-los de uma só vez. Esta implementação inclui verificação de relacionamentos para evitar a exclusão de registros que possuem dependências.

Atualmente, esta funcionalidade está disponível para:
- Inquilinos
- Imóveis
- Tomadores

Para mais detalhes sobre a implementação, consulte a [documentação de exclusão em lote](docs/batch_delete.md).

### Correção do Processamento de CNPJ

Foi corrigido o processamento de CNPJ no campo de discriminação de notas fiscais importadas via XML. Agora o sistema utiliza um campo unificado `cpf_cnpj` para armazenar tanto CPF quanto CNPJ, garantindo consistência no banco de dados.

## Instalação

1. Clone o repositório para o diretório web do seu servidor
2. Importe o esquema do banco de dados localizado em `sql/schema.sql`
3. Configure o arquivo `application/config/database.php` com as credenciais do seu banco de dados
4. Configure o arquivo `application/config/config.php` ajustando a `base_url` para o endereço do seu servidor
5. Crie o diretório `uploads/xml/` na raiz do projeto e garanta permissões de escrita

## Uso

1. Acesse o sistema pelo navegador
2. Faça login com as credenciais padrão (usuário: admin, senha: admin)
3. Acesse o menu "Importação de XML" para importar notas fiscais
4. Gerencie inquilinos, imóveis e tomadores pelos respectivos menus
5. Utilize a funcionalidade de exclusão em lote nas listagens selecionando os checkboxes

## Documentação

A documentação completa do sistema está disponível no diretório `docs/`.