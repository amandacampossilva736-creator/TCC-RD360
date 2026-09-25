function abrirMenu() {
    const menu = document.querySelector(".menu");
    if (menu) menu.classList.toggle("fechado");
}

function mostrarFormulario(id) {
    const formulario = document.getElementById(id);
    if (formulario) formulario.style.display = "block";
}

function fecharFormulario(id) {
    const formulario = document.getElementById(id);
    if (formulario) formulario.style.display = "none";
}

function fecharFormularioObra() {
    const form = document.getElementById("form-obra");
    if (form) form.reset();

    const id = document.getElementById("id_obra");
    if (id) id.value = "";

    const titulo = document.getElementById("titulo-form-obra");
    if (titulo) titulo.textContent = "Nova Obra";

    const botao = document.getElementById("botaoSalvarObra");
    if (botao) botao.textContent = "Salvar";

    fecharFormulario("formularioObra");
}

function fecharFormularioAsBuilt() {
    const form = document.getElementById("form-asbuilt");
    if (form) form.reset();

    const id = document.getElementById("n_as_built");
    if (id) id.value = "";

    const titulo = document.getElementById("titulo-form-asbuilt");
    if (titulo) titulo.textContent = "Novo As Built";

    fecharFormulario("formularioAsBuilt");
}

function fecharFormularioPonto() {
    const form = document.getElementById("form-ponto");
    if (form) form.reset();

    const id = document.getElementById("id_ponto");
    if (id) id.value = "";

    const titulo = document.getElementById("titulo-form-ponto");
    if (titulo) titulo.textContent = "Novo Ponto Coletado";

    const botao = document.getElementById("botao-salvar-ponto");
    if (botao) botao.textContent = "Salvar";

    fecharFormulario("formularioPonto");
}

function fecharFormularioVao() {
    const form = document.getElementById("form-vao");
    if (form) form.reset();

    const id = document.getElementById("id_vao");
    if (id) id.value = "";

    const titulo = document.getElementById("titulo-form-vao");
    if (titulo) titulo.textContent = "Novo Vão";

    fecharFormulario("formularioVao");
}

function verificarLogin() {
    fetch("php/verificar_login.php", { cache: "no-store" })
        .then(res => res.json())
        .then(data => {
            const pagina = window.location.pathname;

            if (!data.logado &&
                !pagina.includes("login.html") &&
                !pagina.includes("cadastro.html") &&
                !pagina.endsWith("/") &&
                !pagina.includes("index.html")) {

                window.location.href = "login.html";
            }
        })
        .catch(() => {});
}

function carregarObras() {
    fetch("php/buscar_obras.php", { cache: "no-store" })
        .then(res => res.json())
        .then(obras => {

            const tabela = document.getElementById("tabela-obras");

            if (tabela) {
                tabela.innerHTML = "";

                obras.forEach(obra => {

                    tabela.innerHTML += `
                    <tr>
                        <td>${esc(obra.projeto_obra)}</td>
                        <td>${esc(obra.nota)}</td>
                        <td>${esc(obra.titulo)}</td>
                        <td>${esc(obra.municipio)}</td>
                        <td>${esc(obra.id_investimento)}</td>
                        <td>${esc(obra.data_energizacao)}</td>
                        <td>
                            <button class="btn-editar"
                                onclick="editarObra(${Number(obra.id_obra)})">
                                Editar
                            </button>

                            <button class="btn-editar"
                                onclick="abrirResumoObra(${Number(obra.id_obra)})">
                                Resumo
                            </button>

                            <button class="btn-excluir"
                                onclick="excluirObra(${Number(obra.id_obra)})">
                                Excluir
                            </button>
                        </td>
                    </tr>`;
                });
            }

            preencherSelectObras("obra-asbuilt", obras);
            preencherSelectObras("obra-ponto", obras);
        })
        .catch(() =>
            console.error("Não foi possível carregar as obras.")
        );
}

function preencherSelectObras(idSelect, obras) {

    const select = document.getElementById(idSelect);

    if (!select) return;

    const valorAtual = select.value;

    select.innerHTML =
        '<option value="">Selecione...</option>';

    obras.forEach(obra => {

        const option = document.createElement("option");

        option.value = obra.id_obra;

        option.textContent =
            `${obra.projeto_obra} - ${obra.titulo}`;

        select.appendChild(option);
    });

    if (valorAtual) {
        select.value = valorAtual;
    }
}

function carregarMunicipios() {

    fetch("php/buscar_municipios.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const datalist =
                document.getElementById("municipios");

            if (!datalist) return;

            datalist.innerHTML = "";

            lista.forEach(municipio => {

                const option =
                    document.createElement("option");

                option.value = municipio.nome;

                datalist.appendChild(option);
            });
        });
}

function editarObra(id) {

    fetch(
        "php/buscar_obra.php?id=" +
        encodeURIComponent(id),
        {
            cache: "no-store"
        }
    )
        .then(res => res.json())
        .then(obra => {

            if (obra.erro) {
                alert(obra.erro);
                return;
            }

            document.getElementById("id_obra").value =
                obra.id_obra;

            document.getElementById("projeto_obra").value =
                obra.projeto_obra;

            document.getElementById("nota_obra").value =
                obra.nota || "";

            document.getElementById("titulo_obra").value =
                obra.titulo;

            document.getElementById("municipio").value =
                obra.municipio;

            document.getElementById("id_investimento").value =
                obra.id_investimento;

            document.getElementById("qtd_postes").value =
                obra.qtd_postes;

            document.getElementById("uc_previsto").value =
                obra.uc_previsto;

            document.getElementById("data_energizacao").value =
                obra.data_energizacao;

            document.getElementById("titulo-form-obra").textContent =
                "Editar Obra";

            document.getElementById("botaoSalvarObra").textContent =
                "Atualizar";

            mostrarFormulario("formularioObra");
        });
}

function excluirObra(id) {

    if (!confirm("Deseja excluir esta obra?")) return;

    window.location.href =
        "php/excluir_obra.php?id=" +
        encodeURIComponent(id);
}

function abrirResumoObra(id) {
    fetch("php/buscar_resumo_obra.php?id=" + encodeURIComponent(id), {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(dados => {
            if (dados.erro) {
                alert(dados.erro);
                return;
            }

            const resumo = document.getElementById("resumo-obra");
            const dadosObra = document.getElementById("dados-resumo-obra");
            const tabelaAsbuilt = document.getElementById("tabela-resumo-asbuilt");
            const tabelaPontos = document.getElementById("tabela-resumo-pontos");
            const tabelaVaos = document.getElementById("tabela-resumo-vaos");

            document.getElementById("titulo-resumo-obra").textContent =
                "Resumo da Obra - " + (dados.obra.titulo || "");

            dadosObra.innerHTML =
                "Projeto: " + esc(dados.obra.projeto_obra || "") +
                " | Nota: " + esc(dados.obra.nota || "");

            tabelaAsbuilt.innerHTML = "";

            if (dados.as_built.length === 0) {
                tabelaAsbuilt.innerHTML =
                    '<tr><td colspan="4">Nenhum As Built relacionado a esta obra.</td></tr>';
            } else {
                dados.as_built.forEach(item => {
                    tabelaAsbuilt.innerHTML += `
                    <tr>
                        <td>${esc(item.n_as_built)}</td>
                        <td>${esc(item.responsavel || "")}</td>
                        <td>${esc(item.status || "")}</td>
                        <td>${esc(item.data_fiscalizacao || "")}</td>
                    </tr>`;
                });
            }

            tabelaPontos.innerHTML = "";

            if (dados.pontos.length === 0) {
                tabelaPontos.innerHTML =
                    '<tr><td colspan="4">Nenhum ponto relacionado a esta obra.</td></tr>';
            } else {
                dados.pontos.forEach(item => {
                    tabelaPontos.innerHTML += `
                    <tr>
                        <td>${esc(item.id_ponto)}</td>
                        <td>${esc(item.nota_obra || "")}</td>
                        <td>${esc(item.latitude || "")}</td>
                        <td>${esc(item.longitude || "")}</td>
                    </tr>`;
                });
            }

            tabelaVaos.innerHTML = "";

            if (dados.vaos.length === 0) {
                tabelaVaos.innerHTML =
                    '<tr><td colspan="4">Nenhum vão relacionado a esta obra.</td></tr>';
            } else {
                dados.vaos.forEach(item => {
                    tabelaVaos.innerHTML += `
                    <tr>
                        <td>${esc(item.fk_pontos_coletados_id_ponto_origem)}</td>
                        <td>${esc(item.fk_pontos_coletados_id_ponto_destino)}</td>
                        <td>${esc(item.distancia || "")}</td>
                        <td>${esc(item.tipo_condutor || "")}</td>
                    </tr>`;
                });
            }

            resumo.style.display = "block";
            resumo.scrollIntoView({ behavior: "smooth", block: "start" });
        })
        .catch(() => {
            alert("Não foi possível carregar o resumo da obra.");
        });
}

function fecharResumoObra() {
    const resumo = document.getElementById("resumo-obra");
    if (resumo) {
        resumo.style.display = "none";
    }
}

function carregarAsbuilt() {

    fetch("php/buscar_asbuilt.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const tabela =
                document.getElementById("tabela-asbuilt");

            if (!tabela) return;

            tabela.innerHTML = "";

            lista.forEach(item => {

                tabela.innerHTML += `
                <tr>
                    <td>${esc(item.n_as_built)}</td>
                    <td>${esc(item.responsavel)}</td>
                    <td>${esc(item.obra)}</td>
                    <td>${esc(item.status)}</td>
                    <td>${esc(item.data_fiscalizacao)}</td>
                    <td>
                        <button class="btn-editar"
                            onclick="editarAsbuilt(${Number(item.n_as_built)})">
                            Editar
                        </button>

                        <button class="btn-excluir"
                            onclick="excluirAsbuilt(${Number(item.n_as_built)})">
                            Excluir
                        </button>
                    </td>
                </tr>`;
            });
        })
        .catch(() =>
            console.error("Não foi possível carregar os As Built.")
        );
}

function carregarStatus() {

    fetch("php/buscar_status.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const select =
                document.getElementById("status-asbuilt");

            if (!select) return;

            select.innerHTML =
                '<option value="">Selecione...</option>';

            lista.forEach(item => {

                const option =
                    document.createElement("option");

                option.value = item.id_status;
                option.textContent = item.nome_status;

                select.appendChild(option);
            });
        });
}

function carregarAsbuiltSelect(idObra = "") {

    return fetch("php/buscar_asbuilt.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const select = document.getElementById("asbuilt-ponto");

            if (!select) return;

            select.innerHTML = '<option value="">Nenhum</option>';

            lista.forEach(item => {

                if (idObra && Number(item.fk_obra_id_obra) !== Number(idObra)) {
                    return;
                }

                const option = document.createElement("option");
                option.value = item.n_as_built;
                option.textContent =
                    "As Built " + item.n_as_built + " - " + item.obra;

                select.appendChild(option);
            });
        })
        .catch(() => {});
}

function editarAsbuilt(id) {

    fetch(
        "php/buscar_asbuilt_item.php?id=" +
        encodeURIComponent(id),
        {
            cache: "no-store"
        }
    )
        .then(res => res.json())
        .then(item => {

            if (item.erro) {
                alert(item.erro);
                return;
            }

            document.getElementById("n_as_built").value =
                item.n_as_built;

            document.getElementById("responsavel").value =
                item.responsavel;

            document.getElementById("obra-asbuilt").value =
                item.fk_obra_id_obra;

            document.getElementById("status-asbuilt").value =
                item.fk_status_id_status;

            document.getElementById("data_fiscalizacao").value =
                item.data_fiscalizacao;

            document.getElementById("observacoes").value =
                item.observacoes || "";

            document.getElementById("titulo-form-asbuilt").textContent =
                "Editar As Built";

            mostrarFormulario("formularioAsBuilt");
        });
}

function excluirAsbuilt(id) {

    if (!confirm("Deseja excluir este As Built?")) return;

    window.location.href =
        "php/excluir_asbuilt.php?id=" +
        encodeURIComponent(id);
}

function verFotosPonto(id) {
    window.open(
        "php/ver_fotos_ponto.php?id=" + encodeURIComponent(id),
        "fotoPonto"
    );
}

function carregarPontos() {

    fetch("php/buscar_pontos.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const tabela =
                document.getElementById("tabela-pontos");

            if (!tabela) return;

            tabela.innerHTML = "";

            lista.forEach(ponto => {

                tabela.innerHTML += `
                <tr>
                    <td>${esc(ponto.id_ponto)}</td>
                    <td>${esc(ponto.nota_obra)}</td>
                    <td>${esc(ponto.obra)}</td>
                    <td>${esc(ponto.latitude)}</td>
                    <td>${esc(ponto.longitude)}</td>

                    <td>
                        <button class="btn-editar"
                            onclick="editarPonto(${Number(ponto.id_ponto)})">
                            Editar
                        </button>

                        <button type="button" class="btn-editar"
                            onclick="verFotosPonto(${Number(ponto.id_ponto)})">
                            Ver Foto
                        </button>

                        <button class="btn-excluir"
                            onclick="excluirPonto(${Number(ponto.id_ponto)})">
                            Excluir
                        </button>
                    </td>
                </tr>`;
            });
        })
        .catch(() => {

            console.error(
                "Não foi possível carregar os pontos."
            );
        });
}

function editarPonto(id) {
    fetch("php/buscar_ponto.php?id=" + encodeURIComponent(id), {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(ponto => {

            if (ponto.erro) {
                alert(ponto.erro);
                return;
            }

            document.getElementById("id_ponto").value = ponto.id_ponto;

            document.getElementById("obra-ponto").value =
                ponto.fk_obra_id_obra;

            document.getElementById("nota-obra").value =
                ponto.nota_obra || "";

            carregarAsbuiltSelect(ponto.fk_obra_id_obra).then(() => {
                document.getElementById("asbuilt-ponto").value =
                    ponto.fk_as_built_n_as_built || "";
            });

            document.getElementById("latitude").value =
                ponto.latitude;

            document.getElementById("longitude").value =
                ponto.longitude;

            document.getElementById("tipo_poste").value =
                ponto.tipo_poste || "";

            document.getElementById("material_poste").value =
                ponto.material_poste || "";

            document.getElementById("altura_poste").value =
                ponto.altura_poste || "";

            document.getElementById("esforco_poste").value =
                ponto.esforco_poste || "";

            document.getElementById("tipo_equipamento").value =
                ponto.tipo_equipamento || "";

            document.getElementById("potencia").value =
                ponto.potencia || "";

            document.getElementById("placa").value =
                ponto.placa || "";

            document.getElementById("cia").value =
                ponto.cia || "";

            document.getElementById("estrutura_primaria").value =
                ponto.estrutura_primaria || "";

            document.getElementById("estrutura_secundaria").value =
                ponto.estrutura_secundaria || "";

            document.getElementById("titulo-form-ponto").textContent =
                "Editar Ponto Coletado";

            mostrarFormulario("formularioPonto");

            const botao = document.getElementById("botao-salvar-ponto");

            if (botao) {
                botao.textContent = "Atualizar";
            }
        })
        .catch(() => {
            alert("Não foi possível carregar o ponto.");
        });
}

function excluirPonto(id) {

    if (!confirm("Deseja excluir este ponto?")) {
        return;
    }

    window.location.href =
        "php/excluir_ponto.php?id=" +
        encodeURIComponent(id);
}

function carregarDadosObraPonto(id) {
    const nota = document.getElementById("nota-obra");
    if (!nota) return;

    if (!id) {
        nota.value = "";
        carregarAsbuiltSelect();
        return;
    }

    fetch("php/buscar_obra.php?id=" + encodeURIComponent(id), { cache: "no-store" })
        .then(res => res.json())
        .then(obra => {
            nota.value = obra.nota || "";
            return carregarAsbuiltSelect(id);
        })
        .then(() => {
            const atual = document.getElementById("asbuilt-ponto").value;
            if (!atual) document.getElementById("asbuilt-ponto").value = "";
        });
}


function carregarPerfil() {

    fetch("php/buscar_perfil.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(usuario => {

            if (usuario.erro) return;

            const nome =
                document.getElementById("nome-usuario");

            const email =
                document.getElementById("email-usuario");

            if (nome) {
                nome.value = usuario.nome || "";
            }

            if (email) {
                email.value = usuario.email || "";
            }
        });
}

function obterLocalizacao() {

    if (!navigator.geolocation) {

        alert(
            "A localização não está disponível neste navegador."
        );

        return;
    }

    navigator.geolocation.getCurrentPosition(

        pos => {

            document.getElementById("latitude").value =
                pos.coords.latitude.toFixed(8);

            document.getElementById("longitude").value =
                pos.coords.longitude.toFixed(8);
        },

        () => {

            alert(
                "Não foi possível obter sua localização. " +
                "Verifique a permissão de localização."
            );
        },

        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

function exportarExcel(tipo) {

    window.location.href =
        "php/exportar_excel.php?tabela=" +
        encodeURIComponent(tipo);
}

function esc(valor) {

    const div =
        document.createElement("div");

    div.textContent = valor || "";

    return div.innerHTML;
}

function carregarVaos() {

    fetch("php/buscar_vaos.php", {
        cache: "no-store"
    })
        .then(res => res.json())
        .then(lista => {

            const tabela =
                document.getElementById("tabela-vaos");

            if (tabela) {

                tabela.innerHTML = "";

                lista.forEach(v => {

                    tabela.innerHTML += `
                    <tr>
                        <td>
                            ${esc(v.nota_origem)}
                            —
                            ${esc(v.obra_origem)}
                        </td>

                        <td>
                            ${esc(v.nota_destino)}
                            —
                            ${esc(v.obra_destino)}
                        </td>

                        <td>
                            ${esc(v.distancia)}
                        </td>

                        <td>
                            ${esc(v.tipo_condutor)}
                        </td>

                        <td>

                            <button class="btn-editar"
                                onclick="editarVao(${Number(v.id_vao)})">
                                Editar
                            </button>

                            <button class="btn-excluir"
                                onclick="excluirVao(${Number(v.id_vao)})">
                                Excluir
                            </button>

                        </td>
                    </tr>`;
                });
            }

            carregarPontosParaVao();

        })
        .catch(() =>
            console.error("Não foi possível carregar os vãos.")
        );
}

function carregarPontosParaVao() {

    return fetch(
        "php/buscar_pontos.php",
        {
            cache: "no-store"
        }
    )
        .then(res => res.json())
        .then(lista => {

            const origem =
                document.getElementById("ponto-origem");

            const destino =
                document.getElementById("ponto-destino");

            if (!origem || !destino) {
                return lista;
            }

            const valorOrigem =
                origem.value;

            const valorDestino =
                destino.value;

            origem.innerHTML =
                '<option value="">Selecione...</option>';

            destino.innerHTML =
                '<option value="">Selecione...</option>';

            lista.forEach(ponto => {

                const texto =
                    `Ponto ${ponto.id_ponto} — Nota ${ponto.nota_obra} — ${ponto.obra}`;

                const op1 =
                    document.createElement("option");

                op1.value =
                    ponto.id_ponto;

                op1.textContent =
                    texto;

                origem.appendChild(op1);

                const op2 =
                    document.createElement("option");

                op2.value =
                    ponto.id_ponto;

                op2.textContent =
                    texto;

                destino.appendChild(op2);
            });

            if (valorOrigem) {
                origem.value = valorOrigem;
            }

            if (valorDestino) {
                destino.value = valorDestino;
            }

            return lista;

        })
        .catch(() => {

            console.error(
                "Não foi possível carregar os pontos para os vãos."
            );

            return [];
        });
}

function editarVao(id) {

    fetch(
        "php/buscar_vao.php?id=" +
        encodeURIComponent(id),
        {
            cache: "no-store"
        }
    )
        .then(res => res.json())
        .then(vao => {

            if (vao.erro) {
                alert(vao.erro);
                return;
            }

            carregarPontosParaVao().then(() => {

                document.getElementById("id_vao").value =
                    vao.id_vao;

                document.getElementById("ponto-origem").value =
                    vao.fk_pontos_coletados_id_ponto_origem;

                document.getElementById("ponto-destino").value =
                    vao.fk_pontos_coletados_id_ponto_destino;

                document.getElementById("tipo_condutor").value =
                    vao.tipo_condutor || "";

                document.getElementById("distancia").value =
                    vao.distancia;

                document.getElementById("titulo-form-vao").textContent =
                    "Editar Vão";

                const botao =
                    document.getElementById("botao-salvar-vao");

                if (botao) {
                    botao.textContent = "Atualizar";
                }

                mostrarFormulario("formularioVao");
            });
        })
        .catch(() => {
            alert("Não foi possível carregar o vão.");
        });
}

function excluirVao(id) {

    if (!confirm("Deseja excluir este vão?")) {
        return;
    }

    window.location.href =
        "php/excluir_vao.php?id=" +
        encodeURIComponent(id);
}