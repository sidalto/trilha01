<h1 align="center" style="font-size: 40px">WJCrypto - A moeda do E-commerce</h1>

<p align="center" style="font-size: 23px">App desenvolvido durante a Trilha Backend 01 da Academia 
  <a href="https://webjump.com.br">
  Webjump <img src="https://emoji.slack-edge.com/T06B26EUW/webjump/5858e20b484131a5.png" width="20px" height="20px"/> 
  </a>
</p>
<h3 align="center">
    Status: 🚧  Concluído/Refatorando...  🚧
</h3>
<br />
<p align="center" style="font-size: 25px">
 <a href="#objetivo">Objetivo</a>&nbsp; •&nbsp;
 <a href="#tecnologias">Tecnologias</a>&nbsp; •&nbsp; 
 <a href="#instalacao">Instalação</a>&nbsp; •&nbsp;  
 <a href="#rotas">Rotas</a>&nbsp; •&nbsp; 
 <a href="#contribuicao">Contribuição</a>
<br />
<p id="objetivo">
  Objetivo: Software Bancário para Crypto Moeda
  <h4>💸 Operações realizadas:</h4>
  <ul>
    <li>Saque</li>
    <li>Depósito</li>
    <li>Transferência</li>
    <li>Extrato</li>
    <li>Pagamento</li>
  </ul>
</p>
🌎 API Rest: <a href="http://45.63.111.91/trilha01">Aqui</a>
<p id="tecnologias">
  <h4>🛠 Tecnologias Utilizadas:</h4>
  <ul>
    <li>PHP 8.0</li>
    <li>Mysql</li>
    <li>Docker</li>
    <ul>
      <li>
        <a href="https://github.com/sprintcube/docker-compose-lamp">Docker LAMP</a></li>
      </li>
    </ul>
    <li>Linux</li>
    <li>Apache</li>
    <li>Libs</li>
    <ul>
      <li><a href="https://github.com/skipperbent/simple-php-router">Simple Router</a></li>
      <li><a href="https://github.com/firebase/php-jwt">JWT Token</a></li>
      <li><a href="https://github.com/Seldaek/monolog">Monolog</a></li>
    </ul>
  </ul>
</p>
<p id="instalacao">
  ⚙️ Instalação
  <ol>
    <li>Instale o Docker: </li>
      <ul>
        <li>
          sudo apt update
        </li>
        <li>
          sudo apt install apt-transport-https ca-certificates curl gnupg2 software-properties-common
        </li>
        <li>
          curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -
        </li>
        <li>
          sudo add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
        </li>
        <li>
          sudo apt update
        </li>
        <li>
          apt-cache policy docker-ce
        </li>
        <li>
          sudo apt install docker-ce
        </li>
        <li>
          sudo systemctl status docker
        </li>
      </ul>
    <li>Clone o respositório do Docker LAMP:</li>
    <ul>
      <li>
        git clone <a href="https://github.com/sprintcube/docker-compose-lamp.git">https://github.com/sprintcube/docker-compose-lamp.git</a>
      </li>
      <li>cd docker-compose-lamp/</li>
      <li>cp sample.env .env</li>
      <li>Modifique o .env caso queira</li>
      <li>docker-compose up -d</li>
      <li>Acesse: <a href="http://localhost">http://localhost</a></li>
    </ul>
    <li>
      Clone o projeto dentro da pasta /seu_path_aqui/docker-compose-lamp/www/
      <ul>
        <li>
          git clone <a href="https://github.com/sidalto/trilha01.git">https://github.com/sidalto/trilha01.git</a>
        </li>
        <li>
          cd ./trilha01
        </li>
      </ul>
    </li>
    <li>
      Instale as dependências do projeto
      <ul>
        <li>
          composer install
        </li>
      </ul>
    </li>
    <li>
      Dê permissão para gravar no arquivo de log da aplicação
      <ul>
        <li>
          chmod 666 /app/Logs/system.log
        </li>
      </ul>
    </li>
    <li>
      Importe o arquivo de banco de dados wjcrypto.sql
      <ul>
        <li>
          Acesse o phpmyadmin: <a href="http://localhost:8080">http://localhost:8080</a>
        </li>
        <li>
          Clique no menu superior <a href="http://localhost:8080/index.php?route=/server/import">"Importar"</a>
        </li>
        <li>
          Clique no botão "choose file" carregue o arquivo wjcrypto.sql contido dentro da pasta da aplicação e clique no botão "executar"
        </li>
        <li>
          Acesse novamente a página inicial do <a href="http://localhost:8080">phpmyadmin</a> e confira se o banco e as tabelas foram criadas 
        </li>
      </ul>
    </li>
  </ol>
</p>
<p id="rotas">
  🛣️ Rotas da API: <br />
  <table border="1">
    <thead>
      <tr>
        <th>Método HTTP</th>
        <th>Rota</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>POST</td>
        <td><a href="http://localhost/trilha01/auth">http://localhost/trilha01/auth</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/logout">http://localhost/trilha01/logout</a></td>
      </tr>
      <tr>
        <td>POST</td>
        <td><a href="http://localhost/trilha01/customer">http://localhost/trilha01/customer</a></td>
      </tr>
      <tr>
        <td>POST</td>
        <td><a href="http://localhost/trilha01/company">http://localhost/trilha01/company</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/dashboard">http://localhost/trilha01/dashboard</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/companies">http://localhost/trilha01/companies</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/company/{company}">http://localhost/trilha01/company/{company}</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/company/{company}">http://localhost/trilha01/company/{company}</a></td>
      </tr>
      <tr>
        <td>DELETE</td>
        <td><a href="http://localhost/trilha01/company/{company}">http://localhost/trilha01/company/{company}</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/customers">http://localhost/trilha01/customers</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/customer/{customer}">http://localhost/trilha01/customer/{customer}</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/customer/{customer}">http://localhost/trilha01/customer/{customer}</a></td>
      </tr>
      <tr>
        <td>DELETE</td>
        <td><a href="http://localhost/trilha01/customer/{customer}">http://localhost/trilha01/customer/{customer}</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/accounts/{customer}">http://localhost/trilha01/accounts/{customer}</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/account/{account}/customer/{customer}">http://localhost/trilha01/account/{account}/customer/{customer}</a></td>
      </tr>
      <tr>
        <td>POST</td>
        <td><a href="http://localhost/trilha01/account">http://localhost/trilha01/account</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/account/{account}">http://localhost/trilha01/account/{account}</a></td>
      </tr>
      <tr>
        <td>DELETE</td>
        <td><a href="http://localhost/trilha01/account/{account}/customer/{customer}">http://localhost/trilha01/account/{account}/customer/{customer}</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/transactions/{account}">http://localhost/trilha01/transactions/{account}</a></td>
      </tr>
      <tr>
        <td>GET</td>
        <td><a href="http://localhost/trilha01/transactions/{account}/{initialDate}/{finalDate}">http://localhost/trilha01/transactions/{account}/{initialDate}/{finalDate}</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/transaction/{account}/withdraw">http://localhost/trilha01/transaction/{account}/withdraw</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/transaction/{account}/deposit">http://localhost/trilha01/transaction/{account}/deposit</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/transaction/{account}/transfer">http://localhost/trilha01/transaction/{account}/transfer</a></td>
      </tr>
      <tr>
        <td>PUT</td>
        <td><a href="http://localhost/trilha01/transaction/{account}/payment">http://localhost/trilha01/transaction/{account}/payment</a></td>
      </tr>
    </tbody>
  </table>
</p>
<p id="contribuicao">
  Contribuidores:
  <ul>
    <li><a href="https://github.com/sidalto">Sidalto Pereira</a></li>
    <li><a href="https://br.linkedin.com/in/vinicius-gsantos">Vinícius G. Santos</a></li>
  </ul>
</p>
