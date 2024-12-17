import axios from 'axios';

export function useGlobalFunctions() {
  const deleteResidence = (id) => {
    if (confirm("Are you sure you want to delete this residence?")) {
      axios.delete(`/residences/${id}`).then(() => {
        window.location.reload();
      });
    }
  };

  return { deleteResidence };
}
