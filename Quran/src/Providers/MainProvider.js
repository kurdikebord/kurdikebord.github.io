import React, { useEffect, useState } from "react";
import { fetchAllReaders, fetchAllSuras } from "../api/fetchApi";

export const mainContext = React.createContext();

const MainProvider = ({ children }) => {
  const initCurrReciter = {
    id: null,
    name: "",
    suras: [],
    server: null,
    rewaya: "",
    count: "",
  };

  const initCurrSura = {
    number: null,
    name: "",
    translate: "",
    index: null,
  };

  //* fetch all needed data
  const [data, setData] = useState(null);
  const [surasNames, setSurasNames] = useState([]);
  //
  const [currReciter, setCurrReciter] = useState(initCurrReciter);
  const [currSura, setCurrSura] = useState(initCurrSura);

  useEffect(() => {
    const fetching = async () => setData(await fetchAllReaders());
    fetching();
  }, []);

  useEffect(() => {
    const fetching = async () => setSurasNames(await fetchAllSuras());
    fetching();
  }, []);

  return (
    <mainContext.Provider
      value={{
        data,
        currReciter,
        currSura,
        surasNames,

        setCurrReciter,
        setCurrSura,
      }}
    >
      {children}
    </mainContext.Provider>
  );
};

export default MainProvider;
