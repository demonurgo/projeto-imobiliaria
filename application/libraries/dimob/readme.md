# Leiaute
## R01 - Ficha 01 - Dados Iniciais

### Neste registro são geradas as informações da filial matriz.

|     |     |     |     |
| --- | --- | --- | --- |  
| Nº  | Campo | Tam. | Documentação |
| 01  | Tipo | 3   | Fixo "R01" |
| 02  | CNPJ do declarante | 14  | E070FIL.NUMGCG |
| 03  | Ano-calendário | 4   | Ano do campo EdatIni da entrada do relatório |
| 04  | Declaração Retificadora | 1   | Parâmetro EdecRet da tela de entrada |
| 05  | Número do Recibo (se for retificadora) | 10  | Parâmetro EnumRec da tela de entrada |
| 06  | Situação Especial | 1   | Parâmetro EsitEsp da tela de entrada |
| 07  | Data do evento situação especial | 8   | Parâmetro EdatEve da tela de entrada |
| 08  | Código da situação especial | 2   | Parâmetro EcodSit da tela de entrada |
| 09  | Nome Empresarial | 60  | E070FIL.NOMFIL |
| 10  | CPF do Responsável pela pessoa jurídica perante à RFB | 11  | E070FIL.CTBCCR |
| 11  | Endereço completo do contribuinte | 120 | E070FIL.ENDFIL |
| 12  | UF do Contribuinte | 2   | E070FIL.SIGUFS |
| 13  | Código do Município do Contribuinte | 4   | E008CEP.CODFIS |
| 14  | Reservado | 20  |     |
| 15  | Reservado | 10  |     |
| 16  | Delimitador de Registro | 2   |     |


## R02 - Ficha 02 - Locação

### Neste registro são exibidas as informações dos pagamentos relativos às operações de locação/sublocação e intermediação de locação realizados no ano de referência, discriminados mensalmente por rendimento bruto, valor da comissão e imposto retido na fonte, quando houver.


|     |     |     |     |
| --- | --- | --- | --- |  
| Nº  | Campo | Tam. | Documentação |
| 01  | Tipo | 3   | Fixo "R02" |
| 02  | CNPJ do declarante | 14  | E070FIL.NUMCGC |
| 03  | Ano-calendário | 4   | Ano do campo EdatIni da entrada do relatório |
| 04  | Sequencial da Locação | 5   | E661OIM.NUMLCT |
| 05  | CPF/CNPJ do Locador | 14  | E661OIM.CNPFOR |
| 06  | Nome/Nome Empresarial do Locador | 60  | E661OIM.NOMFOR |
| 07  | CPF/CNPJ do Locatário | 14  | E661OIM.CNPCLI |
| 08  | Nome/Nome Empresarial do Locatário | 60  | E661OIM.NOMCLI |
| 09  | Número do Contrato | 6   | E661OIM.NUMCTR |
| 10  | Data do Contrato | 8   | E661OIM.DATCTR |
| 11  | Valor do Aluguel no mês de Janeiro | 14  | E661OIM.VLRLCT |
| 12  | Valor da Comissão no mês de Janeiro | 14  | E661OIM.VLRCOM |
| 13  | Valor do Imposto no mês de Janeiro | 14  | E661OIM.VLRIRE |
| 14  | Valor do Aluguel no mês de Fevereiro | 14  | E661OIM.VLRLCT |
| 15  | Valor da Comissão no mês de Fevereiro | 14  | E661OIM.VLRCOM |
| 16  | Valor do Imposto no mês de Fevereiro | 14  | E661OIM.VLRIRE |
| 17  | Valor do Aluguel no mês de Março | 14  | E661OIM.VLRLCT |
| 18  | Valor da Comissão no mês de Março | 14  | E661OIM.VLRCOM |
| 19  | Valor do Imposto no mês de Março | 14  | E661OIM.VLRIRE |
| 20  | Valor do Aluguel no mês de Abril | 14  | E661OIM.VLRLCT |
| 21  | Valor da Comissão no mês de Abril | 14  | E661OIM.VLRCOM |
| 22  | Valor do Imposto no mês de Abril | 14  | E661OIM.VLRIRE |
| 23  | Valor do Aluguel no mês de Maio | 14  | E661OIM.VLRLCT |
| 24  | Valor da Comissão no mês de Maio | 14  | E661OIM.VLRCOM |
| 25  | Valor do Imposto no mês de Maio | 14  | E661OIM.VLRIRE |
| 26  | Valor do Aluguel no mês de Junho | 14  | E661OIM.VLRLCT |
| 27  | Valor da Comissão no mês de Junho | 14  | E661OIM.VLRCOM |
| 28  | Valor do Imposto no mês de Junho | 14  | E661OIM.VLRIRE |
| 29  | Valor do Aluguel no mês de Julho | 14  | E661OIM.VLRLCT |
| 30  | Valor da Comissão no mês de Julho | 14  | E661OIM.VLRCOM |
| 31  | Valor do Imposto no mês de Julho | 14  | E661OIM.VLRIRE |
| 32  | Valor do Aluguel no mês de Agosto | 14  | E661OIM.VLRLCT |
| 33  | Valor da Comissão no mês de Agosto | 14  | E661OIM.VLRCOM |
| 34  | Valor do Imposto no mês de Agosto | 14  | E661OIM.VLRIRE |
| 35  | Valor do Aluguel no mês de Setembro | 14  | E661OIM.VLRLCT |
| 36  | Valor da Comissão no mês de Setembro | 14  | E661OIM.VLRCOM |
| 37  | Valor do Imposto no mês de Setembro | 14  | E661OIM.VLRIRE |
| 38  | Valor do Aluguel no mês de Outubro | 14  | E661OIM.VLRLCT |
| 39  | Valor da Comissão no mês de Outubro | 14  | E661OIM.VLRCOM |
| 40  | Valor do Imposto no mês de Outubro | 14  | E661OIM.VLRIRE |
| 41  | Valor do Aluguel no mês de Novembro | 14  | E661OIM.VLRLCT |
| 42  | Valor da Comissão no mês de Novembro | 14  | E661OIM.VLRCOM |
| 43  | Valor do Imposto no mês de Novembro | 14  | E661OIM.VLRIRE |
| 44  | Valor do Aluguel no mês de Dezembro | 14  | E661OIM.VLRLCT |
| 45  | Valor da Comissão no mês de Dezembro | 14  | E661OIM.VLRCOM |
| 46  | Valor do Imposto no mês de Dezembro | 14  | E661OIM.VLRIRE |
| 47  | Tipo do Imóvel | 1   | E056IMO.TIPIMO |
| 48  | Endereço do Imóvel | 60  | E056IMO.ENDIMO |
| 49  | CEP | 8   | E056IMO.CEPIMO |
| 50  | Código do Município do Imóvel | 4   | E008CEP.CODFIS |
| 51  | Reservado | 20  |     |
| 52  | UF  | 2   | E056IMO.SIGUFS |
| 53  | Reservado | 10  |     |
| 54  | Delimitador de Registro | 2   |     |

## R03 - Ficha 03 - Construção e Incorporação de Imóveis

### Neste registro são apresentadas as informações das operações de construção, incorporação e loteamento contratadas no ano de referência.

### As informações são relativas a todos os imóveis comercializados por pessoas jurídicas e equiparadas que construírem, incorporarem e lotearem, mesmo que tenha havido a intermediação de terceiros. Além da construção de imóveis por pessoas jurídicas constituídas para este fim em nome próprio, de seus condôminos ou sócios.

### São exibidas todas as vendas independentemente de terem sido efetuadas antes, durante ou após a construção.


|     |     |     |     |
| --- | --- | --- | --- |  
| Nº  | Campo | Tam. | Documentação |
| 01  | Tipo | 3   | Fixo "R03" |
| 02  | CNPJ do declarante | 14  | E070FIL.NUMCGC |
| 03  | Ano-calendário | 4   | Ano do campo EdatIni da entrada do relatório |
| 04  | Sequencial da Venda | 5   | E661UIV.LCTUIV |
| 05  | CPF/CNPJ do Comprador | 14  | E085CLI.CGCCPF |
| 06  | Nome/Nome Empresarial do Comprador | 60  | E085CLI.NOMCLI |
| 07  | Número do Contrato | 6   | E661UIV.NUMCTR |
| 08  | Data do Contrato | 8   | E661UIV.DATVEN |
| 09  | Valor da Operação | 14  | E661UIV.VLRVEN |
| 10  | Valor Pago no Ano | 14  | E661IVR.VLRTOT |
| 11  | Tipo do Imóvel | 1   | E056IMO.TIPIMO |
| 12  | Endereço do Imóvel | 60  | E056IMO.ENDIMO |
| 13  | CEP | 8   | E056IMO.CEPIMO |
| 14  | Código do Município do Imóvel | 4   | E008CEP.CODFIS |
| 15  | Reservado | 20  |     |
| 16  | UF  | 2   | E056IMO.SIGUFS |
| 17  | Reservado | 10  |     |
| 18  | Delimitador de Registro | 2   |     |


## R04 - Ficha 04 - Intermediação de Venda

### Neste registro são apresentas as informações das operações de intermediação de vendas contratadas no ano de referência.


|     |     |     |     |
| --- | --- | --- | --- |  
| Nº  | Campo | Tam. | Documentação |
| 01  | Tipo | 3   | Fixo "R04" |
| 02  | CNPJ do declarante | 14  | E070FIL.NUMCGC |
| 03  | Ano-calendário | 4   | Ano do campo EdatIni da entrada do relatório |
| 04  | Sequencial da Venda | 5   | E661OIM.NUMLCT |
| 05  | CPF/CNPJ do Comprador | 14  | E661OIM.CNPFOR |
| 06  | Nome/Nome Empresarial do Comprador | 60  | E661OIM.NOMFOR |
| 07  | CPF/CNPJ do Vendedor | 14  |     |
| 08  | Nome/Nome Empresarial do Vendedor | 60  |     |
| 09  | Número do Contrato | 6   | E661OIM.NUMCTR |
| 10  | Data do Contrato | 8   | E661OIM.DATCTR |
| 11  | Valor da Venda | 14  | E661OIM.VLRLCT |
| 12  | Valor da Comissão | 14  | E661OIM.VLRCOM |
| 13  | Tipo do Imóvel | 1   |     |
| 14  | Endereço do Imóvel | 60  |     |
| 15  | CEP | 8   |     |
| 16  | Código do Município do Imóvel | 4   |     |
| 17  | Reservado | 20  |     |
| 18  | UF  | 2   |     |
| 19  | Reservado | 10  |     |
| 20  | Delimitador de Registro | 2   |     |


[Fonte](https://documentacao.senior.com.br/goup/5.10.2/menu_controladoria/f661oim-leiaute-dimob.htm)