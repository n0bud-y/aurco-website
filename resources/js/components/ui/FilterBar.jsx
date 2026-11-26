/** Filter chips; optional `counts` ({ option: number }) shows how many items each one holds. */
export default function FilterBar({ options, value, onChange, label, counts }) {
  return (
    <div className="filter-bar" role="group" aria-label={label}>
      {options.map((option) => (
        <button
          key={option}
          type="button"
          className="filter-bar__btn"
          aria-pressed={value === option}
          onClick={() => onChange(option)}
        >
          {option}
          {counts?.[option] != null && <span className="filter-bar__count">{counts[option]}</span>}
        </button>
      ))}
    </div>
  )
}
