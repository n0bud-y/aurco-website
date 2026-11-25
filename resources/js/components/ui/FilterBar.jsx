export default function FilterBar({ options, value, onChange, label }) {
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
        </button>
      ))}
    </div>
  )
}
