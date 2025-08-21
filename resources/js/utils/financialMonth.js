// Utilitário para calcular o mês financeiro baseado no ciclo do dia 25
export function getCurrentFinancialMonth() {
  const now = new Date();
  const currentDay = now.getDate();
  
  // Se estivermos antes do dia 25, o mês financeiro ainda é o mês atual
  // Se estivermos no dia 25 ou depois, o mês financeiro já é o próximo mês
  if (currentDay >= 25) {
    const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1);
    return {
      month: nextMonth.getMonth() + 1,
      year: nextMonth.getFullYear()
    };
  } else {
    // Estamos antes do dia 25, então o mês financeiro atual é o mês atual
    return {
      month: now.getMonth() + 1,
      year: now.getFullYear()
    };
  }
}

export function getFinancialPeriod(month, year) {
  // Retorna o período do ciclo financeiro para um determinado mês/ano
  const startDate = new Date(year, month - 1, 25);
  startDate.setMonth(startDate.getMonth() - 1); // Mês anterior
  
  const endDate = new Date(year, month - 1, 25);
  
  return {
    startDate,
    endDate,
    description: `${startDate.toLocaleDateString('pt-BR')} até ${endDate.toLocaleDateString('pt-BR')}`
  };
}
